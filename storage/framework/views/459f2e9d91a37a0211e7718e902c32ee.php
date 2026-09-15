<?php $__env->startSection('title', 'Master Superadmin Console'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Master Header -->
    <div class="bg-gradient-to-r from-slate-950 via-purple-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-purple-900/40">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/20 border border-purple-500/30 text-purple-300 rounded-full text-xs font-bold">
                <span>👑 Superadmin Root Control Authority</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Stayease Master Governance</h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Otoritas tertinggi pengelolaan akun multi-role, verifikasi KYC KTP pengguna, persetujuan pencairan dana escrow perbankan, dan pemantauan log audit keamanan.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('superadmin.users')); ?>" class="px-5 py-2.5 bg-purple-500 hover:bg-purple-400 text-slate-950 font-black text-xs rounded-xl shadow transition flex items-center gap-1.5">
                <span>👥 Kelola User & Role</span>
            </a>
        </div>
    </div>

    <!-- Master Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Pengguna Terdaftar</span>
                <span class="p-1.5 bg-purple-100 text-purple-700 rounded-lg">👥</span>
            </div>
            <div class="text-2xl font-black text-slate-900"><?php echo e($totalUsers); ?> Akun</div>
            <div class="text-[10px] text-purple-700 font-bold"><?php echo e($totalMitras); ?> Mitra • <?php echo e($totalAdmins); ?> Admin</div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Volume GMV</span>
                <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg">📈</span>
            </div>
            <div class="text-xl font-black text-emerald-700">Rp <?php echo e(number_format($totalGmv, 0, ',', '.')); ?></div>
            <div class="text-[10px] text-emerald-600 font-bold">● Transaksi Sukses</div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Total Fee Platform (5%)</span>
                <span class="p-1.5 bg-teal-100 text-teal-700 rounded-lg">💰</span>
            </div>
            <div class="text-xl font-black text-slate-900">Rp <?php echo e(number_format($platformFeeTotal, 0, ',', '.')); ?></div>
            <div class="text-[10px] text-teal-700 font-bold">● Net Revenue Stayease</div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-400 font-bold">
                <span>Deposit Jaminan Escrow</span>
                <span class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">🔒</span>
            </div>
            <div class="text-xl font-black text-blue-700">Rp <?php echo e(number_format($totalEscrow, 0, ',', '.')); ?></div>
            <div class="text-[10px] text-slate-500 font-medium">Tersimpan di Escrow Vault</div>
        </div>

    </div>

    <!-- Pending High-Value Payout Approvals -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-sm text-slate-900">Permintaan Pencairan Dana Mitra (Persetujuan Superadmin)</h3>
                <p class="text-xs text-slate-400">Verifikasi otorisasi pencairan dana omzet sewa ke rekening bank pemilik.</p>
            </div>
            <a href="<?php echo e(route('superadmin.payouts')); ?>" class="text-xs font-bold text-purple-700 hover:underline">Kelola Semua Payout &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Mitra Pemilik</th>
                        <th class="p-3">Nominal Pencairan</th>
                        <th class="p-3">Rekening Bank</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Otorisasi Superadmin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3">
                                <div class="font-bold text-slate-900"><?php echo e($p->mitra->name); ?></div>
                                <div class="text-[10px] text-slate-400"><?php echo e($p->mitra->email); ?></div>
                            </td>
                            <td class="p-3 font-black text-slate-900">
                                Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?>

                            </td>
                            <td class="p-3">
                                <div class="font-bold text-slate-800"><?php echo e($p->bank_name); ?></div>
                                <div class="text-[10px] text-slate-400 font-mono"><?php echo e($p->account_number); ?> a/n <?php echo e($p->account_holder); ?></div>
                            </td>
                            <td class="p-3">
                                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">MENUNGGU OTORISASI</span>
                            </td>
                            <td class="p-3 text-right space-x-1">
                                <form action="<?php echo e(route('superadmin.payouts.approve', $p->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[11px] shadow transition">
                                        ✓ Setujui Transfer Bank
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400">Tidak ada pengajuan payout yang tertunda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Audit Logs Stream -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-sm text-slate-900">Audit Log Keamanan & Aktivitas Sensitif</h3>
                <p class="text-xs text-slate-400">Pencatatan riwayat pergantian role, status akun, dan persetujuan dana.</p>
            </div>
            <a href="<?php echo e(route('superadmin.audit_logs')); ?>" class="text-xs font-bold text-purple-700 hover:underline">Buka Semua Log &rarr;</a>
        </div>

        <div class="space-y-2">
            <?php $__currentLoopData = $recentAuditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <span class="p-1.5 bg-purple-100 text-purple-700 rounded-lg text-xs font-mono font-bold">
                            <?php echo e($log->action); ?>

                        </span>
                        <div>
                            <span class="font-bold text-slate-900"><?php echo e($log->user->name ?? 'System'); ?></span>
                            <span class="text-slate-400">pada <?php echo e($log->entity_type); ?> (ID: <?php echo e($log->entity_id); ?>)</span>
                        </div>
                    </div>

                    <div class="text-right text-[11px] text-slate-400">
                        <span class="font-mono"><?php echo e($log->ip_address); ?></span> • <?php echo e($log->created_at->diffForHumans()); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/superadmin/index.blade.php ENDPATH**/ ?>