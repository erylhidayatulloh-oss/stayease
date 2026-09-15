<?php $__env->startSection('title', 'Permintaan & Manajemen Booking'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Manajemen Calon Penyewa & Booking</h1>
        <p class="text-xs text-slate-500">Periksa data identitas calon penyewa, terima pengajuan sewa, dan pantau status check-in.</p>
    </div>

    <!-- Booking Requests Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Kode Booking</th>
                        <th class="p-4">Calon Penyewa (KYC)</th>
                        <th class="p-4">Properti & Kamar</th>
                        <th class="p-4">Jadwal & Durasi</th>
                        <th class="p-4">Total Pembayaran</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi Persetujuan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-emerald-700">
                                <?php echo e($b->booking_code); ?>

                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900"><?php echo e($b->tenant_name); ?></div>
                                <div class="text-[11px] text-slate-500 font-mono">NIK: <?php echo e($b->tenant_nik); ?></div>
                                <div class="text-[11px] text-emerald-700">WA: <?php echo e($b->tenant_phone); ?></div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800"><?php echo e($b->property->title); ?></div>
                                <div class="text-[11px] text-slate-400"><?php echo e($b->unit->name); ?></div>
                            </td>
                            <td class="p-4 text-slate-700">
                                <strong><?php echo e($b->check_in_date->format('d M Y')); ?></strong>
                                <span class="text-[10px] block text-slate-400">s/d <?php echo e($b->check_in_date->copy()->addMonths($b->duration_months)->format('d M Y')); ?> (<?php echo e($b->duration_months); ?> Bln)</span>
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                Rp <?php echo e(number_format($b->grand_total, 0, ',', '.')); ?>

                                <span class="text-[10px] block text-slate-400 font-normal">Bayar: <?php echo e(strtoupper($b->payment_method)); ?></span>
                            </td>
                            <td class="p-4">
                                <?php if($b->status === 'active_lease'): ?>
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">AKTIF TINGGAL</span>
                                <?php elseif($b->status === 'paid'): ?>
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-full">LUNAS (SIAP CHECK-IN)</span>
                                <?php elseif($b->status === 'completed'): ?>
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-black rounded-full">SELESAI SEWA</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-full"><?php echo e(strtoupper($b->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right space-x-1">
                                <?php if($b->status === 'paid'): ?>
                                    <form action="<?php echo e(route('mitra.bookings.update_status', $b->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="status" value="active_lease">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[11px] transition shadow">
                                            ✓ Setujui Check-in
                                        </button>
                                    </form>
                                <?php elseif($b->status === 'active_lease'): ?>
                                    <form action="<?php echo e(route('mitra.bookings.update_status', $b->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-[11px] transition">
                                            Selesaikan Sewa
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-slate-400 text-[11px]">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada data pemesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/mitra/bookings.blade.php ENDPATH**/ ?>