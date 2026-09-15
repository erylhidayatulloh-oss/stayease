<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Stayease Dashboard'); ?> | Official Rent Platform Indonesia</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Top Universal Role Switcher Bar (Top UX Priority) -->
    <header class="bg-slate-950 text-white px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 text-xs z-50 sticky top-0">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-bold text-slate-300">Stayease Security & Multi-Role Panel</span>
            <span class="text-slate-600">|</span>
            <span class="text-slate-400 font-mono text-[11px]">Active Role: <strong class="text-emerald-400 uppercase font-black"><?php echo e(auth()->user()->role ?? 'GUEST'); ?></strong></span>
            <span class="text-slate-600">|</span>
            <a href="/" class="text-emerald-400 hover:text-emerald-300 font-bold flex items-center gap-1">
                <span>🏠 Kembali ke Situs Utama</span>
            </a>
        </div>

        <!-- 1-Click Role Switcher Buttons: Superadmin-only impersonation now that real per-role login exists -->
        <?php if((auth()->user()->role ?? '') === 'superadmin'): ?>
        <div class="flex items-center gap-1.5 overflow-x-auto">
            <span class="text-slate-400 font-bold text-[11px] mr-1 hidden sm:inline">⚡ Lihat Sebagai (Superadmin):</span>
            
            <a href="<?php echo e(route('role.switch', 'user')); ?>" 
               class="px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1 bg-slate-800 hover:bg-slate-700 text-slate-300">
                <span>👤 Penyewa (User)</span>
            </a>

            <a href="<?php echo e(route('role.switch', 'mitra')); ?>" 
               class="px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1 bg-slate-800 hover:bg-slate-700 text-slate-300">
                <span>🏠 Pemilik (Mitra)</span>
            </a>

            <a href="<?php echo e(route('role.switch', 'admin')); ?>" 
               class="px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1 bg-slate-800 hover:bg-slate-700 text-slate-300">
                <span>🛡️ Admin Operasional</span>
            </a>

            <a href="<?php echo e(route('role.switch', 'superadmin')); ?>" 
               class="px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1 bg-emerald-600 text-white shadow">
                <span>👑 Superadmin</span>
            </a>
        </div>
        <?php else: ?>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="px-2.5 py-1 rounded-lg font-bold transition flex items-center gap-1 bg-slate-800 hover:bg-rose-700 text-slate-300 hover:text-white">
                <span>🚪 Keluar</span>
            </button>
        </form>
        <?php endif; ?>
    </header>

    <div class="flex-1 flex overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shrink-0">
            <div>
                <!-- Brand Header -->
                <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-base shadow-md shadow-emerald-500/20">
                        S
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-900 text-base leading-tight">Stay<span class="text-emerald-600">ease</span></div>
                        <div class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                            <?php if(auth()->user()->isSuperAdmin()): ?>
                                Superadmin Console
                            <?php elseif(auth()->user()->isAdmin()): ?>
                                Admin Operations
                            <?php elseif(auth()->user()->isMitra()): ?>
                                Mitra Landlord Panel
                            <?php else: ?>
                                Tenant Portal
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Nav Links Based on Role -->
                <nav class="p-3 space-y-1">
                    
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        <!-- Superadmin Links -->
                        <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('superadmin.dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📊</span> Platform Analytics
                        </a>
                        <a href="<?php echo e(route('superadmin.users')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('superadmin.users*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">👥</span> User & Role Management
                        </a>
                        <a href="<?php echo e(route('superadmin.payouts')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('superadmin.payouts*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">💳</span> Mitra Payout Approvals
                        </a>
                        <a href="<?php echo e(route('superadmin.audit_logs')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('superadmin.audit_logs*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🔒</span> Security Audit Logs
                        </a>
                        <a href="<?php echo e(route('superadmin.settings')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('superadmin.settings*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">⚙️</span> Platform Settings
                        </a>
                    
                    <?php elseif(auth()->user()->isAdmin()): ?>
                        <!-- Admin Links -->
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📊</span> Admin Overview
                        </a>
                        <a href="<?php echo e(route('admin.verification_queue')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.verification_queue*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🛡️</span> Verification Queue
                        </a>
                        <a href="<?php echo e(route('admin.bookings')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.bookings*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🧾</span> Booking & Payments
                        </a>
                        <a href="<?php echo e(route('admin.cities')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.cities*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📍</span> SEO City Hubs
                        </a>
                        <a href="<?php echo e(route('admin.promos')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.promos*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🏷️</span> Promos & Vouchers
                        </a>
                        <a href="<?php echo e(route('admin.reviews')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('admin.reviews*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">⭐</span> Review Moderation
                        </a>

                    <?php elseif(auth()->user()->isMitra()): ?>
                        <!-- Mitra Links -->
                        <a href="<?php echo e(route('mitra.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📊</span> Ringkasan Mitra
                        </a>
                        <a href="<?php echo e(route('mitra.properties')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.properties*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🏠</span> Kelola Properti & Kamar
                        </a>
                        <a href="<?php echo e(route('mitra.bookings')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.bookings*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📋</span> Permintaan Booking
                        </a>
                        <a href="<?php echo e(route('mitra.finance')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.finance*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">💰</span> Keuangan & Pencairan
                        </a>
                        <a href="<?php echo e(route('mitra.reviews')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.reviews*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">💬</span> Ulasan Penyewa
                        </a>
                        <a href="<?php echo e(route('mitra.messages')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('mitra.messages*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📨</span> Pesan Penyewa
                        </a>

                    <?php else: ?>
                        <!-- User / Tenant Links -->
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('user.dashboard') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">🏠</span> Beranda Penyewa
                        </a>
                        <a href="<?php echo e(route('user.bookings')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('user.bookings*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">📑</span> Sewa & Kontrak Saya
                        </a>
                        <a href="<?php echo e(route('user.wishlist')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('user.wishlist*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">❤️</span> Favorit Tersimpan
                        </a>
                        <a href="<?php echo e(route('user.messages')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('user.messages*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">💬</span> Pesan Pemilik
                        </a>
                        <a href="<?php echo e(route('user.profile')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs <?php echo e(request()->routeIs('user.profile*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                            <span class="text-base">👤</span> Profil & KTP
                        </a>
                    <?php endif; ?>

                </nav>
            </div>

            <!-- Profile & Active User Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 space-y-3">
                <div class="flex items-center gap-3">
                    <img src="<?php echo e(auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80'); ?>" 
                         alt="<?php echo e(auth()->user()->name); ?>" 
                         class="w-10 h-10 rounded-xl object-cover border-2 border-emerald-500 shadow-sm shrink-0">
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-slate-900 truncate"><?php echo e(auth()->user()->name); ?></div>
                        <div class="text-[10px] text-slate-400 truncate"><?php echo e(auth()->user()->email); ?></div>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full py-2 text-[11px] font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition flex items-center justify-center gap-1.5">
                        <span>🚪</span> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50">
            
            <!-- Notification Flash Messages -->
            <?php if(session('success')): ?>
                <div class="m-6 mb-0 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-xs text-emerald-900 shadow-sm animate-fadeIn">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✅</span>
                        <strong><?php echo e(session('success')); ?></strong>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="m-6 mb-0 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between text-xs text-rose-900 shadow-sm animate-fadeIn">
                    <div class="flex items-center gap-2">
                        <span class="text-base">⚠️</span>
                        <strong><?php echo e(session('error')); ?></strong>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Dynamic Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

        </div>

    </div>

</body>
</html>
<?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>