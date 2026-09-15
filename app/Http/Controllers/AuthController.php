<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected array $roleDashboardRoutes = [
        'superadmin' => 'superadmin.dashboard',
        'admin' => 'admin.dashboard',
        'mitra' => 'mitra.dashboard',
        'user' => 'user.dashboard',
    ];

    /**
     * Show the role-based login page. Each role tab has its own themed
     * panel, but they all post to the same handler below.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route($this->roleDashboardRoutes[Auth::user()->role] ?? 'user.dashboard');
        }

        $role = in_array($request->query('role'), array_keys($this->roleDashboardRoutes))
            ? $request->query('role')
            : 'user';

        return view('auth.login', [
            'activeRole' => $role,
            'intended' => $request->query('intended'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|in:user,mitra,admin,superadmin',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->withInput($request->only('email', 'role'));
        }

        if (in_array($user->status, ['suspended', 'banned'])) {
            $message = $user->status === 'banned'
                ? 'Akun Anda telah diblokir permanen oleh Superadmin. Hubungi CS Stayease untuk informasi lebih lanjut.'
                : 'Akun Anda sedang ditangguhkan sementara oleh Superadmin.';

            return back()->withErrors(['email' => $message])->withInput($request->only('email', 'role'));
        }

        // Superadmin can log into any role's dashboard (universal access);
        // everyone else must log in through their own role's tab.
        if ($user->role !== $credentials['role'] && $user->role !== 'superadmin') {
            return back()
                ->withErrors(['email' => 'Akun ini bukan akun ' . strtoupper($credentials['role']) . '. Silakan pilih tab role yang sesuai.'])
                ->withInput($request->only('email', 'role'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        AuditLog::log('USER_LOGIN', 'User', $user->id, ['email' => $user->email], $user->id);

        $intended = $request->input('intended');
        if ($intended && str_starts_with($intended, '/')) {
            return redirect($intended)->with('success', 'Berhasil masuk. Selamat datang kembali, ' . $user->name . '!');
        }

        $targetRole = $credentials['role'] === 'superadmin' ? 'superadmin' : $user->role;
        return redirect()->route($this->roleDashboardRoutes[$targetRole] ?? 'user.dashboard')
            ->with('success', 'Berhasil masuk. Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari akun.');
    }
}
