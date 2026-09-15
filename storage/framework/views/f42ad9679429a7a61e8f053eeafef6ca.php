<?php $__env->startSection('title', 'Manajemen Pengguna & Otoritas Role'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Pengguna & Otoritas Role</h1>
            <p class="text-xs text-slate-500">Kelola akun seluruh pengguna, ubah hak akses (User, Mitra, Admin, Superadmin), dan status blokir.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-3xl p-4 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="text-slate-400 font-bold">Filter Role:</span>
            <a href="<?php echo e(route('superadmin.users')); ?>" class="px-3 py-1 rounded-xl font-bold <?php echo e(!request('role') ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700'); ?>">Semua</a>
            <a href="<?php echo e(route('superadmin.users', ['role' => 'user'])); ?>" class="px-3 py-1 rounded-xl font-bold <?php echo e(request('role') === 'user' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700'); ?>">User</a>
            <a href="<?php echo e(route('superadmin.users', ['role' => 'mitra'])); ?>" class="px-3 py-1 rounded-xl font-bold <?php echo e(request('role') === 'mitra' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700'); ?>">Mitra</a>
            <a href="<?php echo e(route('superadmin.users', ['role' => 'admin'])); ?>" class="px-3 py-1 rounded-xl font-bold <?php echo e(request('role') === 'admin' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700'); ?>">Admin</a>
            <a href="<?php echo e(route('superadmin.users', ['role' => 'superadmin'])); ?>" class="px-3 py-1 rounded-xl font-bold <?php echo e(request('role') === 'superadmin' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700'); ?>">Superadmin</a>
        </div>

        <form action="<?php echo e(route('superadmin.users')); ?>" method="GET" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama / email / HP..." class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-purple-500">
            <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white font-bold rounded-xl">Cari</button>
        </form>
    </div>

    <!-- Users Master Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Pengguna</th>
                        <th class="p-4">Role Akses</th>
                        <th class="p-4">Status Akun</th>
                        <th class="p-4">Verifikasi KTP</th>
                        <th class="p-4">Aktivitas</th>
                        <th class="p-4 text-right">Otorisasi & Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo e($u->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80'); ?>" class="w-9 h-9 rounded-xl object-cover border border-slate-200">
                                    <div>
                                        <div class="font-bold text-slate-900"><?php echo e($u->name); ?></div>
                                        <div class="text-[11px] text-slate-400"><?php echo e($u->email); ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono"><?php echo e($u->phone); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <form action="<?php echo e(route('superadmin.users.update_role', $u->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="role" onchange="this.form.submit()" class="px-2.5 py-1 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 cursor-pointer">
                                        <option value="user" <?php echo e($u->role === 'user' ? 'selected' : ''); ?>>👤 User (Penyewa)</option>
                                        <option value="mitra" <?php echo e($u->role === 'mitra' ? 'selected' : ''); ?>>🏠 Mitra (Pemilik)</option>
                                        <option value="admin" <?php echo e($u->role === 'admin' ? 'selected' : ''); ?>>🛡️ Admin Operasional</option>
                                        <option value="superadmin" <?php echo e($u->role === 'superadmin' ? 'selected' : ''); ?>>👑 Superadmin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4">
                                <form action="<?php echo e(route('superadmin.users.toggle_status', $u->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg text-[11px] font-bold border <?php echo e($u->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-rose-50 text-rose-800 border-rose-300'); ?>">
                                        <option value="active" <?php echo e($u->status === 'active' ? 'selected' : ''); ?>>● Aktif</option>
                                        <option value="suspended" <?php echo e($u->status === 'suspended' ? 'selected' : ''); ?>>⏸ Suspended</option>
                                        <option value="banned" <?php echo e($u->status === 'banned' ? 'selected' : ''); ?>>🚫 Banned</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4">
                                <?php if($u->ktp_verified_at): ?>
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">✓ KYC Valid</span>
                                <?php else: ?>
                                    <form action="<?php echo e(route('superadmin.users.verify_ktp', $u->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="px-2 py-0.5 bg-amber-100 hover:bg-emerald-600 hover:text-white text-amber-800 text-[10px] font-bold rounded-full transition">
                                            Verifikasi Sekarang
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">
                                <?php echo e($u->properties_count); ?> Properti • <?php echo e($u->bookings_count); ?> Sewa
                            </td>
                            <td class="p-4 text-right">
                                <a href="<?php echo e(route('role.switch', $u->role)); ?>" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-[10px] transition">
                                    Impersonate Akun
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada data pengguna yang cocok.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/superadmin/users.blade.php ENDPATH**/ ?>