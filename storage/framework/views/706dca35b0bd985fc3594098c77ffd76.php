<?php $__env->startSection('title', 'Admin Operasional & Verifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 rounded-full text-xs font-bold">
                <span>🛡️ Konsol Manajemen Operasional Stayease</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Admin Control Center</h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Verifikasi kelayakan listing properti baru dari Mitra, validasi transaksi pembayaran QRIS & VA, dan kelola konten SEO wilayah.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.verification_queue')); ?>" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl shadow transition flex items-center gap-1.5">
                <span>🛡️ Antrean Verifikasi (<?php echo e($pendingPropertiesCount); ?>)</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Menunggu Verifikasi</span>
                <span class="p-1.5 bg-amber-100 text-amber-700 rounded-lg">⏳</span>
            </div>
            <div class="text-2xl font-black text-amber-600"><?php echo e($pendingPropertiesCount); ?> Listing</div>
            <div class="text-[10px] text-slate-500 font-medium"><a href="<?php echo e(route('admin.verification_queue')); ?>" class="text-emerald-700 hover:underline">Buka Antrean Audit &rarr;</a></div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Listing Aktif & Tayang</span>
                <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg">✅</span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?php echo e($activePropertiesCount); ?> Properti</div>
            <div class="text-[10px] text-emerald-600 font-bold">● 100% Foto Real</div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Transaksi Sewa</span>
                <span class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">🧾</span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?php echo e($totalBookingsCount); ?> Pesanan</div>
            <div class="text-[10px] text-slate-500 font-medium"><a href="<?php echo e(route('admin.bookings')); ?>" class="text-emerald-700 hover:underline">Monitor Pembayaran &rarr;</a></div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total GMV Volume</span>
                <span class="p-1.5 bg-teal-100 text-teal-700 rounded-lg">💰</span>
            </div>
            <div class="text-xl font-black text-slate-900">Rp <?php echo e(number_format($totalGmv, 0, ',', '.')); ?></div>
            <div class="text-[10px] text-emerald-700 font-bold">● Escrow Aman</div>
        </div>

    </div>

    <!-- Quick Verification Queue Preview -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-sm text-slate-900">Listing Properti Menunggu Persetujuan Admin</h3>
                <p class="text-xs text-slate-400">Verifikasi kelayakan alamat dan keaslian foto sebelum listing ditayangkan ke publik.</p>
            </div>
            <a href="<?php echo e(route('admin.verification_queue')); ?>" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua (<?php echo e($pendingPropertiesCount); ?>) &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Nama Listing</th>
                        <th class="p-3">Mitra Pemilik</th>
                        <th class="p-3">Lokasi & Tipe</th>
                        <th class="p-3">Harga Dasar</th>
                        <th class="p-3 text-right">Aksi Verifikasi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-bold text-slate-900"><?php echo e($p->title); ?></td>
                            <td class="p-3">
                                <div><?php echo e($p->mitra->name); ?></div>
                                <div class="text-[10px] text-slate-400"><?php echo e($p->mitra->phone); ?></div>
                            </td>
                            <td class="p-3"><?php echo e(strtoupper($p->property_type)); ?> • <?php echo e($p->sub_district); ?>, <?php echo e($p->city->name ?? ''); ?></td>
                            <td class="p-3 font-black text-slate-900">Rp <?php echo e(number_format($p->base_price_monthly, 0, ',', '.')); ?>/bln</td>
                            <td class="p-3 text-right space-x-1">
                                <form action="<?php echo e(route('admin.properties.approve', $p->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="verified_official" value="1">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-[11px] shadow transition">
                                        ✓ Setujui (Official Badge)
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400">Tidak ada listing dalam antrean verifikasi saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/admin/index.blade.php ENDPATH**/ ?>