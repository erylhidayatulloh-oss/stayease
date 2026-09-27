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
     * Show the login page. Revisi: there is no longer a role picker here —
     * a single email + password form logs the person into whichever
     * dashboard matches their actual account role.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route($this->roleDashboardRoutes[Auth::user()->role] ?? 'user.dashboard');
        }

        return view('auth.login', [
            'intended' => $request->query('intended'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->withInput($request->only('email'));
        }

        if (in_array($user->status, ['suspended', 'banned'])) {
            $message = $user->status === 'banned'
                ? 'Akun Anda telah diblokir permanen oleh Superadmin. Hubungi CS Stayease untuk informasi lebih lanjut.'
                : 'Akun Anda sedang ditangguhkan sementara oleh Superadmin.';

            return back()->withErrors(['email' => $message])->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        AuditLog::log('USER_LOGIN', 'User', $user->id, ['email' => $user->email], $user->id);

        $intended = $request->input('intended');
        if ($intended && str_starts_with($intended, '/')) {
            return redirect($intended)->with('success', 'Berhasil masuk. Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->route($this->roleDashboardRoutes[$user->role] ?? 'user.dashboard')
            ->with('success', 'Berhasil masuk. Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari akun.');
    }

    /**
     * Revisi: Daftar sebagai User/Penyewa biasa (bukan Mitra) — pendaftaran
     * publik paling sederhana, langsung aktif & login.
     */
    public function showUserRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->roleDashboardRoutes[Auth::user()->role] ?? 'user.dashboard');
        }

        return view('auth.register_user');
    }

    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'active',
        ]);

        AuditLog::log('USER_SELF_REGISTERED', 'User', $user->id, ['email' => $user->email], $user->id);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Stayease, ' . $user->name . '.');
    }

    /**
     * Revisi: Daftar Jadi Mitra — pendaftaran publik untuk pemilik properti,
     * memilih tingkatan Mitra Biasa (kelola & bayar sendiri, potongan lebih
     * kecil) atau Mitra Pro (semua di-handle Admin Stayease, potongan lebih
     * besar sebagai kompensasi jasa pengelolaan penuh).
     */
    public function showMitraRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->roleDashboardRoutes[Auth::user()->role] ?? 'user.dashboard');
        }

        return view('auth.register_mitra');
    }

    public function registerMitra(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'mitra_tier' => 'required|in:reguler,pro',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'mitra',
            'mitra_tier' => $data['mitra_tier'],
            'status' => 'active',
        ]);

        AuditLog::log('MITRA_SELF_REGISTERED', 'User', $user->id, [
            'email' => $user->email,
            'mitra_tier' => $user->mitra_tier,
        ], $user->id);

        Auth::login($user);
        $request->session()->regenerate();

        $welcomeMessage = $user->isMitraPro()
            ? 'Pendaftaran Mitra Pro berhasil! Tim Admin Stayease akan segera menghubungi Anda untuk mengunggah properti pertama.'
            : 'Pendaftaran Mitra Biasa berhasil! Yuk tambahkan listing properti pertama Anda.';

        return redirect()->route('mitra.dashboard')->with('success', $welcomeMessage);
    }
}
