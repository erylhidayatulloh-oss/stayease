<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Payout;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalMitras = User::where('role', 'mitra')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $totalGmv = Booking::whereIn('status', ['paid', 'active_lease', 'completed'])->sum('grand_total');
        $platformFeeTotal = $totalGmv * 0.05; // 5% platform fee
        $totalEscrow = Booking::where('status', 'active_lease')->sum('deposit_total');

        $pendingPayouts = Payout::with('mitra')->where('status', 'pending')->latest()->get();
        $recentAuditLogs = AuditLog::with('user')->latest()->take(6)->get();

        return view('dashboard.superadmin.index', compact(
            'totalUsers',
            'totalMitras',
            'totalAdmins',
            'totalGmv',
            'platformFeeTotal',
            'totalEscrow',
            'pendingPayouts',
            'recentAuditLogs'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $users = $query->withCount(['properties', 'bookings'])->latest()->paginate(15);

        return view('dashboard.superadmin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,mitra,admin,superadmin',
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        AuditLog::log('USER_ROLE_CHANGED', 'User', $user->id, [
            'target_user_email' => $user->email,
            'old_role' => $oldRole,
            'new_role' => $request->role,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', "Role user {$user->name} berhasil diubah menjadi: " . strtoupper($request->role));
    }

    public function toggleUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
        ]);

        $oldStatus = $user->status;
        $user->update(['status' => $request->status]);

        AuditLog::log('USER_STATUS_CHANGED', 'User', $user->id, [
            'target_user_email' => $user->email,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi: " . strtoupper($request->status));
    }

    public function verifyUserKtp(User $user)
    {
        $user->update(['ktp_verified_at' => now()]);

        AuditLog::log('USER_KYC_VERIFIED', 'User', $user->id, [
            'target_user_email' => $user->email,
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', "Identitas KTP {$user->name} berhasil diverifikasi.");
    }

    public function payouts()
    {
        $payouts = Payout::with(['mitra', 'processor'])->latest()->paginate(15);
        return view('dashboard.superadmin.payouts', compact('payouts'));
    }

    public function approvePayout(Request $request, Payout $payout)
    {
        $payout->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'notes' => $request->notes ?? 'Pencairan dana telah ditransfer via RTGS/BI-FAST ke rekening Mitra.',
        ]);

        AuditLog::log('PAYOUT_APPROVED_BY_SUPERADMIN', 'Payout', $payout->id, [
            'mitra_email' => $payout->mitra->email,
            'amount' => $payout->amount,
            'bank' => $payout->bank_name,
        ]);

        return back()->with('success', "Pencairan dana Rp " . number_format($payout->amount, 0, ',', '.') . " ke Mitra {$payout->mitra->name} BERHASIL DISETUJUI.");
    }

    public function rejectPayout(Request $request, Payout $payout)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $payout->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'notes' => $request->notes,
        ]);

        AuditLog::log('PAYOUT_REJECTED_BY_SUPERADMIN', 'Payout', $payout->id, [
            'mitra_email' => $payout->mitra->email,
            'amount' => $payout->amount,
            'reason' => $request->notes,
        ]);

        return back()->with('success', "Permintaan pencairan dana ditolak.");
    }

    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(20);
        return view('dashboard.superadmin.audit_logs', compact('logs'));
    }

    public function settings()
    {
        return view('dashboard.superadmin.settings');
    }
}
