<?php $__env->startSection('title', 'Otorisasi Pencairan Dana Mitra'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Otorisasi Pencairan Dana Mitra (Payouts)</h1>
        <p class="text-xs text-slate-500">Persetujuan pencairan dana omzet sewa ke rekening bank pemilik properti (Mitra).</p>
    </div>

    <!-- Payout Approvals Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4">Mitra Pemilik</th>
                        <th class="p-4">Nominal</th>
                        <th class="p-4">Rekening Tujuan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Diproses Oleh</th>
                        <th class="p-4 text-right">Otorisasi Superadmin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-700 font-mono"><?php echo e($p->created_at->format('d M Y, H:i')); ?></td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900"><?php echo e($p->mitra->name); ?></div>
                                <div class="text-[10px] text-slate-400 font-mono"><?php echo e($p->mitra->email); ?></div>
                            </td>
                            <td class="p-4 font-black text-slate-900 text-sm">
                                Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?>

                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800"><?php echo e($p->bank_name); ?></div>
                                <div class="text-[10px] text-slate-400 font-mono"><?php echo e($p->account_number); ?> a/n <?php echo e($p->account_holder); ?></div>
                            </td>
                            <td class="p-4">
                                <?php if($p->status === 'approved'): ?>
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">DISETUJUI (TRANSFERRED)</span>
                                <?php elseif($p->status === 'pending'): ?>
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">MENUNGGU OTORISASI</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-full">DITOLAK</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">
                                <?php echo e($p->processor->name ?? '-'); ?>

                            </td>
                            <td class="p-4 text-right space-x-1">
                                <?php if($p->status === 'pending'): ?>
                                    <form action="<?php echo e(route('superadmin.payouts.approve', $p->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[11px] shadow transition">
                                            ✓ Setujui Transfer
                                        </button>
                                    </form>

                                    <form action="<?php echo e(route('superadmin.payouts.reject', $p->id)); ?>" method="POST" class="inline" onsubmit="const r = prompt('Masukkan alasan penolakan pencairan dana:'); if(r){ this.notes.value = r; return true; } return false;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="notes" value="">
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-[11px] transition">
                                            Tolak
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-slate-400 text-[11px]">Telah Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada data pengajuan pencairan dana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($payouts->hasPages()): ?>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($payouts->links()); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/superadmin/payouts.blade.php ENDPATH**/ ?>