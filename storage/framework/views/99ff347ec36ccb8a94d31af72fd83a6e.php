<?php $__env->startSection('title', 'Antrean Verifikasi Listing'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Antrean Verifikasi Properti Mitra</h1>
        <p class="text-xs text-slate-500">Lakukan peninjauan mutu foto, alamat, dan kelayakan fasilitas sebelum memberikan lencana 100% Official Verified Stayease.</p>
    </div>

    <div class="space-y-6">
        <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 gap-4">
                    <div>
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-900 font-black text-[10px] rounded-full uppercase">
                            STATUS: MENUNGGU VERIFIKASI
                        </span>
                        <h3 class="text-lg font-black text-slate-900 mt-1"><?php echo e($p->title); ?></h3>
                        <p class="text-xs text-slate-500"><?php echo e($p->address); ?>, <?php echo e($p->sub_district); ?>, <?php echo e($p->city->name ?? ''); ?></p>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block font-bold">Harga Diajukan:</span>
                        <div class="text-base font-black text-emerald-700">Rp <?php echo e(number_format($p->base_price_monthly, 0, ',', '.')); ?>/bln</div>
                    </div>
                </div>

                <!-- Mitra Info & Photos Preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                        <div class="font-bold text-slate-900">Pemilik / Pengelola:</div>
                        <div class="flex items-center gap-2">
                            <img src="<?php echo e($p->mitra->avatar ?? ''); ?>" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <div class="font-bold text-slate-800"><?php echo e($p->mitra->name); ?></div>
                                <div class="text-[11px] text-slate-400"><?php echo e($p->mitra->email); ?></div>
                            </div>
                        </div>
                        <div class="text-[11px] text-emerald-700">WhatsApp: <?php echo e($p->mitra->phone); ?></div>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <div class="font-bold text-xs text-slate-900">Foto Kamar yang Diunggah:</div>
                        <div class="flex gap-2 overflow-x-auto">
                            <?php $__currentLoopData = $p->images ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e($img); ?>" class="w-32 h-20 object-cover rounded-xl border border-slate-200 shrink-0">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Facilities & Description -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
                    <div class="font-bold text-slate-900">Deskripsi & Fasilitas:</div>
                    <p class="text-slate-600 leading-relaxed"><?php echo e($p->description); ?></p>
                    <div class="flex flex-wrap gap-1 pt-1">
                        <?php $__currentLoopData = $p->facilities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-0.5 bg-white border border-slate-200 rounded text-[10px] text-slate-700 font-semibold"><?php echo e($f); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Admin Actions: Approve or Reject -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-[11px] text-slate-400">
                        Diajukan pada: <?php echo e($p->created_at->format('d M Y, H:i')); ?> WIB
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <!-- Reject Form -->
                        <form action="<?php echo e(route('admin.properties.reject', $p->id)); ?>" method="POST" class="flex-1 sm:flex-initial" onsubmit="const r = prompt('Masukkan alasan penolakan untuk Mitra:'); if(r){ this.reason.value = r; return true; } return false;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="reason" value="">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl transition">
                                ✕ Tolak Listing
                            </button>
                        </form>

                        <!-- Approve Form -->
                        <form action="<?php echo e(route('admin.properties.approve', $p->id)); ?>" method="POST" class="flex-1 sm:flex-initial">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="verified_official" value="1">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition flex items-center justify-center gap-1.5">
                                <span>✓ Setujui & Berikan Badge Official</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-16 text-center bg-white rounded-3xl border border-slate-200 text-xs text-slate-400 space-y-2">
                <span class="text-3xl block">✨</span>
                <strong>Semua antrean listing properti telah selesai diverifikasi!</strong>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/admin/verification_queue.blade.php ENDPATH**/ ?>