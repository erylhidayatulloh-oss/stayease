<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\MitraDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Middleware\RoleMiddleware;

// Landing / Portal Index -> Public customer storefront (React SPA)
Route::get('/', function () {
    $indexPath = public_path('storefront/index.html');
    if (!file_exists($indexPath)) {
        // Frontend hasn't been built yet (npm run build) — fall back to the account dashboard.
        return redirect()->route('dashboard.entry');
    }
    return response()->file($indexPath);
})->name('home');

// Real role-based authentication (email + password against the users table)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// "Dashboard Saya" entry point -> sends the visitor to their own dashboard if
// already logged in, otherwise to the login page for their last-used role.
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return match (auth()->user()?->role) {
        'superadmin' => redirect()->route('superadmin.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        'mitra' => redirect()->route('mitra.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->name('dashboard.entry');

// 1-Click Role Switcher — kept only as a Superadmin impersonation shortcut
// (Superadmin has universal access), everyone else must log in for real now.
Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switchRole'])->name('role.switch');

// 1. USER (Penyewa / Tenant) Dashboard Routes
Route::prefix('dashboard/user')->middleware([RoleMiddleware::class . ':user,superadmin'])->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/bookings', [UserDashboardController::class, 'bookings'])->name('user.bookings');
    Route::get('/bookings/{booking}/contract', [UserDashboardController::class, 'contract'])->name('user.contract');
    Route::post('/bookings/{booking}/review', [UserDashboardController::class, 'storeReview'])->name('user.review.store');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('user.wishlist');
    Route::delete('/wishlist/{wishlist}', [UserDashboardController::class, 'removeWishlist'])->name('user.wishlist.remove');
    Route::get('/messages', [UserDashboardController::class, 'messages'])->name('user.messages');
    Route::post('/messages', [UserDashboardController::class, 'sendMessage'])->name('user.messages.send');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
});

// 2. MITRA (Pemilik Properti) Dashboard Routes
Route::prefix('dashboard/mitra')->middleware([RoleMiddleware::class . ':mitra,superadmin'])->group(function () {
    Route::get('/', [MitraDashboardController::class, 'index'])->name('mitra.dashboard');
    Route::get('/properties', [MitraDashboardController::class, 'properties'])->name('mitra.properties');
    Route::get('/properties/create', [MitraDashboardController::class, 'createProperty'])->name('mitra.properties.create');
    Route::post('/properties', [MitraDashboardController::class, 'storeProperty'])->name('mitra.properties.store');
    Route::delete('/properties/{property}', [MitraDashboardController::class, 'destroyProperty'])->name('mitra.properties.destroy');
    Route::get('/bookings', [MitraDashboardController::class, 'bookings'])->name('mitra.bookings');
    Route::patch('/bookings/{booking}/status', [MitraDashboardController::class, 'updateBookingStatus'])->name('mitra.bookings.update_status');
    Route::get('/finance', [MitraDashboardController::class, 'finance'])->name('mitra.finance');
    Route::post('/finance/payout', [MitraDashboardController::class, 'requestPayout'])->name('mitra.finance.request_payout');
    Route::get('/reviews', [MitraDashboardController::class, 'reviews'])->name('mitra.reviews');
    Route::post('/reviews/{review}/reply', [MitraDashboardController::class, 'replyReview'])->name('mitra.reviews.reply');
    Route::get('/messages', [MitraDashboardController::class, 'messages'])->name('mitra.messages');
    Route::post('/messages', [MitraDashboardController::class, 'sendMessage'])->name('mitra.messages.send');
});

// 3. ADMIN (Operasional & Konten) Dashboard Routes
Route::prefix('dashboard/admin')->middleware([RoleMiddleware::class . ':admin,superadmin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/verification-queue', [AdminDashboardController::class, 'verificationQueue'])->name('admin.verification_queue');
    Route::post('/properties/{property}/approve', [AdminDashboardController::class, 'approveProperty'])->name('admin.properties.approve');
    Route::post('/properties/{property}/reject', [AdminDashboardController::class, 'rejectProperty'])->name('admin.properties.reject');
    Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
    Route::patch('/bookings/{booking}/payment-status', [AdminDashboardController::class, 'updatePaymentStatus'])->name('admin.bookings.update_payment');
    Route::get('/cities', [AdminDashboardController::class, 'cities'])->name('admin.cities');
    Route::get('/promos', [AdminDashboardController::class, 'promos'])->name('admin.promos');
    Route::post('/promos', [AdminDashboardController::class, 'storePromo'])->name('admin.promos.store');
    Route::get('/reviews', [AdminDashboardController::class, 'reviews'])->name('admin.reviews');
    Route::patch('/reviews/{review}/toggle-status', [AdminDashboardController::class, 'toggleReviewStatus'])->name('admin.reviews.toggle_status');
    Route::post('/reviews/{review}/reply', [AdminDashboardController::class, 'replyReview'])->name('admin.reviews.reply');
});

// 4. SUPERADMIN (Master System) Dashboard Routes
Route::prefix('dashboard/superadmin')->middleware([RoleMiddleware::class . ':superadmin'])->group(function () {
    Route::get('/', [SuperadminDashboardController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/users', [SuperadminDashboardController::class, 'users'])->name('superadmin.users');
    Route::patch('/users/{user}/role', [SuperadminDashboardController::class, 'updateUserRole'])->name('superadmin.users.update_role');
    Route::patch('/users/{user}/status', [SuperadminDashboardController::class, 'toggleUserStatus'])->name('superadmin.users.toggle_status');
    Route::post('/users/{user}/verify-ktp', [SuperadminDashboardController::class, 'verifyUserKtp'])->name('superadmin.users.verify_ktp');
    Route::get('/payouts', [SuperadminDashboardController::class, 'payouts'])->name('superadmin.payouts');
    Route::post('/payouts/{payout}/approve', [SuperadminDashboardController::class, 'approvePayout'])->name('superadmin.payouts.approve');
    Route::post('/payouts/{payout}/reject', [SuperadminDashboardController::class, 'rejectPayout'])->name('superadmin.payouts.reject');
    Route::get('/audit-logs', [SuperadminDashboardController::class, 'auditLogs'])->name('superadmin.audit_logs');
    Route::get('/settings', [SuperadminDashboardController::class, 'settings'])->name('superadmin.settings');
});
