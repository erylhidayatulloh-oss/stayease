<?php $__env->startSection('title', 'Keuangan & Pencairan Omzet'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Keuangan & Pencairan Omzet</h1>
        <p class="text-xs text-slate-500">Laporan pendapatan bersih setelah potongan biaya platform 5% dan riwayat transfer dana ke rekening bank.</p>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <div class="bg-gradient-to-br from-emerald-900 to-slate-900 text-white p-6 rounded-3xl shadow-lg space-y-4">
            <div>
                <span class="text-xs text-emerald-300 font-bold uppercase tracking-wider block">Saldo Siap Dicairkan (Net 95%)</span>
                <div class="text-3xl font-black text-emerald-400 mt-1">
                    Rp <?php echo e(number_format($availableBalance, 0, ',', '.')); ?>

                </div>
            </div>

            <!-- Request Payout Trigger Form -->
            <?php if($availableBalance >= 100000): ?>
                <form action="<?php echo e(route('mitra.finance.request_payout')); ?>" method="POST" class="space-y-3 pt-2 border-t border-emerald-800/60 text-xs">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="amount" value="<?php echo e($availableBalance); ?>">
                    <input type="hidden" name="bank_name" value="<?php echo e($mitra->bank_name ?? 'Bank BCA'); ?>">
                    <input type="hidden" name="account_number" value="<?php echo e($mitra->bank_account_number ?? '5210987654'); ?>">
                    <input type="hidden" name="account_holder" value="<?php echo e($mitra->bank_account_holder ?? $mitra->name); ?>">

                    <div class="text-[11px] text-slate-300">
                        Transfer ke: <strong><?php echo e($mitra->bank_name ?? 'BCA'); ?> - <?php echo e($mitra->bank_account_number ?? '5210987654'); ?></strong>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl shadow transition">
                        ⚡ Tarik Semua Saldo ke Rekening Bank
                    </button>
                </form>
            <?php else: ?>
                <div class="text-[11px] text-slate-400">Saldo minimum untuk penarikan adalah Rp 100.000.</div>
            <?php endif; ?>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 flex flex-col justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase">Total Omzet Bruto</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rp <?php echo e(number_format($totalGrossRevenue, 0, ',', '.')); ?></div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-xs text-slate-500">
                Fee Platform (5%): <strong class="text-rose-600">-Rp <?php echo e(number_format($platformFeeTotal, 0, ',', '.')); ?></strong>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 flex flex-col justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase">Total Telah Dicairkan</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rp <?php echo e(number_format($totalDisbursed, 0, ',', '.')); ?></div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-xs text-emerald-700 font-bold">
                ● Seluruh pencairan berhasil ditransfer
            </div>
        </div>

    </div>

    <!-- Payout History Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 font-extrabold text-sm text-slate-900">
            Riwayat Penarikan Dana & Pencairan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4">Nominal Penarikan</th>
                        <th class="p-4">Rekening Tujuan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-700"><?php echo e($p->created_at->format('d M Y, H:i')); ?> WIB</td>
                            <td class="p-4 font-black text-slate-900">Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800"><?php echo e($p->bank_name); ?></div>
                                <div class="text-[10px] text-slate-400 font-mono"><?php echo e($p->account_number); ?> a/n <?php echo e($p->account_holder); ?></div>
                            </td>
                            <td class="p-4">
                                <?php if($p->status === 'approved'): ?>
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">DITRANSFER (LUNAS)</span>
                                <?php elseif($p->status === 'pending'): ?>
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">PROSES ADMIN</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-full">DITOLAK</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]"><?php echo e($p->notes ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada riwayat penarikan dana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/mitra/finance.blade.php ENDPATH**/ ?>