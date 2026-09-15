<?php $__env->startSection('title', 'Ringkasan Mitra Pemilik'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-full text-xs font-bold">
                <span>🏠 Mitra Resmi Stayease Verified</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, <?php echo e($mitra->name); ?>! 📈</h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Pantau performa okupansi kamar, kelola persetujuan calon penyewa, dan ajukan pencairan omzet sewa Anda secara real-time.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('mitra.properties.create')); ?>" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl shadow transition flex items-center gap-1.5">
                <span>➕ Tambah Listing Properti</span>
            </a>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Omzet -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Omzet Kotor</span>
                <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg">💰</span>
            </div>
            <div class="text-xl font-black text-slate-900">
                Rp <?php echo e(number_format($totalGrossRevenue, 0, ',', '.')); ?>

            </div>
            <div class="text-[10px] text-emerald-700 font-bold">● Saldo Bersih: Rp <?php echo e(number_format($availableBalance, 0, ',', '.')); ?></div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Tingkat Okupansi</span>
                <span class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">📊</span>
            </div>
            <div class="text-xl font-black text-slate-900">
                <?php echo e($occupancyRate); ?>%
            </div>
            <div class="text-[10px] text-slate-500 font-medium"><?php echo e($occupiedRooms); ?> dari <?php echo e($totalRooms); ?> Kamar Terisi</div>
        </div>

        <!-- Active Tenants -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Penyewa Aktif</span>
                <span class="p-1.5 bg-teal-100 text-teal-700 rounded-lg">👥</span>
            </div>
            <div class="text-xl font-black text-slate-900">
                <?php echo e($activeTenantsCount); ?> Orang
            </div>
            <div class="text-[10px] text-emerald-600 font-bold">● Kontrak Terverifikasi</div>
        </div>

        <!-- Total Properties -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Properti</span>
                <span class="p-1.5 bg-amber-100 text-amber-700 rounded-lg">🏢</span>
            </div>
            <div class="text-xl font-black text-slate-900">
                <?php echo e($totalProperties); ?> Listing
            </div>
            <div class="text-[10px] text-slate-500 font-medium"><a href="<?php echo e(route('mitra.properties')); ?>" class="text-emerald-700 hover:underline">Kelola Kamar &rarr;</a></div>
        </div>

    </div>

    <!-- Recent Booking Requests -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-sm text-slate-900">Permintaan & Status Sewa Terbaru</h3>
                <p class="text-xs text-slate-400">Daftar calon penyewa yang mengajukan sewa di hunian Anda.</p>
            </div>
            <a href="<?php echo e(route('mitra.bookings')); ?>" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Kode Booking</th>
                        <th class="p-3">Nama Penyewa</th>
                        <th class="p-3">Properti & Kamar</th>
                        <th class="p-3">Check-in</th>
                        <th class="p-3">Total Tagihan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-mono font-bold text-emerald-700"><?php echo e($b->booking_code); ?></td>
                            <td class="p-3">
                                <div class="font-bold text-slate-900"><?php echo e($b->tenant_name); ?></div>
                                <div class="text-[10px] text-slate-400 font-mono"><?php echo e($b->tenant_phone); ?></div>
                            </td>
                            <td class="p-3"><?php echo e($b->property->title); ?> (<?php echo e($b->unit->name); ?>)</td>
                            <td class="p-3"><?php echo e($b->check_in_date->format('d M Y')); ?> (<?php echo e($b->duration_months); ?> Bln)</td>
                            <td class="p-3 font-bold text-slate-900">Rp <?php echo e(number_format($b->grand_total, 0, ',', '.')); ?></td>
                            <td class="p-3">
                                <?php if($b->status === 'active_lease'): ?>
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">AKTIF TINGGAL</span>
                                <?php elseif($b->status === 'paid'): ?>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-black rounded-full">LUNAS</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-black rounded-full"><?php echo e(strtoupper($b->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 text-right">
                                <a href="<?php echo e(route('mitra.bookings')); ?>" class="px-3 py-1 bg-slate-900 text-white rounded-lg font-bold text-[11px]">
                                    Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400">Belum ada data transaksi booking.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/mitra/index.blade.php ENDPATH**/ ?>