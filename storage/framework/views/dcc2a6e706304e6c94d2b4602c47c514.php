<?php $__env->startSection('title', 'Ulasan & Tanggapan Penyewa'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Ulasan & Rating dari Penyewa</h1>
        <p class="text-xs text-slate-500">Baca masukan penghuni dan berikan tanggapan resmi pengelola untuk meningkatkan reputasi listing.</p>
    </div>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo e($rev->user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80'); ?>" alt="<?php echo e($rev->user->name); ?>" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="font-extrabold text-xs text-slate-900"><?php echo e($rev->user->name); ?></h4>
                            <p class="text-[10px] text-slate-400">Unit: <?php echo e($rev->property->title); ?> • <?php echo e($rev->created_at->format('d M Y')); ?></p>
                        </div>
                    </div>

                    <div class="text-amber-500 font-black text-sm flex items-center gap-1">
                        <span>★</span> <?php echo e($rev->rating); ?>.0
                    </div>
                </div>

                <p class="text-xs text-slate-700 leading-relaxed italic">
                    "<?php echo e($rev->comment); ?>"
                </p>

                <!-- Owner Reply Section -->
                <?php if($rev->owner_reply): ?>
                    <div class="p-3 bg-emerald-50/50 rounded-2xl border border-emerald-200 text-xs space-y-1">
                        <div class="font-bold text-[11px] text-emerald-800">Tanggapan Anda (Pengelola):</div>
                        <p class="text-slate-700"><?php echo e($rev->owner_reply); ?></p>
                    </div>
                <?php else: ?>
                    <form action="<?php echo e(route('mitra.reviews.reply', $rev->id)); ?>" method="POST" class="pt-2 border-t border-slate-100 flex gap-2 text-xs">
                        <?php echo csrf_field(); ?>
                        <input type="text" name="reply" placeholder="Tulis tanggapan terima kasih kepada penyewa..." required class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition">
                            Balas
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 text-xs text-slate-400">
                Belum ada ulasan yang masuk untuk properti Anda.
            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/mitra/reviews.blade.php ENDPATH**/ ?>