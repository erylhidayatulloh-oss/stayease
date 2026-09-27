<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    /**
     * Switch current authenticated session to a specific role's demo user
     */
    public function switchRole(string $role)
    {
        // Only Superadmin may impersonate other roles now that real
        // per-role login exists — everyone else must log out and sign in
        // with their own account.
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            return redirect()->route('login')->with('error', 'Hanya Superadmin yang dapat beralih role secara instan. Silakan login dengan akun Anda sendiri.');
        }

        $validRoles = ['user', 'mitra', 'admin', 'superadmin'];
        if (!in_array($role, $validRoles)) {
            return back()->with('error', 'Role tidak valid.');
        }

        $targetUser = User::where('role', $role)->first();
        if (!$targetUser) {
            return back()->with('error', "User dengan role {$role} tidak ditemukan.");
        }

        // Bug fix: dulu di sini langsung Auth::login() tanpa cek status, jadi
        // kalau akun demo untuk role ini sedang suspended/banned, Superadmin
        // ikut ter-login ke akun itu lalu langsung ditendang keluar + sesinya
        // dihapus oleh RoleMiddleware begitu menyentuh dashboard tujuan.
        if (in_array($targetUser->status, ['suspended', 'banned'])) {
            $statusLabel = $targetUser->status === 'banned' ? 'diblokir (banned)' : 'ditangguhkan (suspended)';
            return back()->with('error', "Tidak bisa beralih ke akun demo role {$role} ({$targetUser->email}) karena akun tersebut sedang {$statusLabel}. Aktifkan dulu akunnya lewat Kelola Pengguna Superadmin.");
        }

        Auth::login($targetUser);

        AuditLog::log('ROLE_SWITCH', 'User', $targetUser->id, [
            'switched_to_role' => $role,
            'user_email' => $targetUser->email,
        ], $targetUser->id);

        $redirectRoute = match ($role) {
            'superadmin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'mitra' => 'mitra.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->route($redirectRoute)->with('success', "Berhasil beralih mode akun ke: " . strtoupper($role) . " ({$targetUser->name})");
    }
}
