<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            // Bug fix: this used to silently auto-login as a demo user with no
            // password check at all, so every dashboard was wide open to
            // anyone. Now guests are sent to the real login page instead.
            return redirect()->route('login', [
                'role' => $roles[0] ?? 'user',
                'intended' => $request->getRequestUri(),
            ]);
        }

        $user = auth()->user();

        if (in_array($user->status, ['suspended', 'banned'])) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $user->status === 'banned'
                ? 'Akun Anda telah diblokir permanen oleh Superadmin. Hubungi CS Stayease untuk informasi lebih lanjut.'
                : 'Akun Anda sedang ditangguhkan sementara oleh Superadmin.';

            return redirect()->route('home')->with('error', $message);
        }

        // Superadmin has universal access 
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Admin has access to admin and lower roles if specified
        if ($user->role === 'admin' && in_array('admin', $roles)) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            // Redirect user to their own role's dashboard
            return match ($user->role) {
                'superadmin' => redirect()->route('superadmin.dashboard')->with('error', 'Dialihkan ke dashboard Superadmin Anda.'),
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Dialihkan ke dashboard Admin Anda.'),
                'mitra' => redirect()->route('mitra.dashboard')->with('error', 'Dialihkan ke dashboard Mitra Anda.'),
                default => redirect()->route('user.dashboard')->with('error', 'Dialihkan ke dashboard Penyewa Anda.'),
            };
        }

        return $next($request);
    }
}
