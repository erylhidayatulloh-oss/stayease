<?php $__env->startSection('title', 'Favorit Tersimpan'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Daftar Hunian Favorit</h1>
        <p class="text-xs text-slate-500">Kumpulan kost, apartemen, dan villa impian yang Anda simpan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $wishlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <img src="<?php echo e($w->property->images[0] ?? ''); ?>" alt="<?php echo e($w->property->title); ?>" class="w-full h-44 object-cover">
                    <div class="p-4 space-y-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 rounded-md text-slate-700 uppercase"><?php echo e($w->property->property_type); ?></span>
                        <h3 class="font-extrabold text-sm text-slate-900 line-clamp-1"><?php echo e($w->property->title); ?></h3>
                        <p class="text-xs text-slate-500"><?php echo e($w->property->address); ?>, <?php echo e($w->property->city->name ?? ''); ?></p>
                        <div class="text-sm font-black text-emerald-700">Rp <?php echo e(number_format($w->property->base_price_monthly, 0, ',', '.')); ?><span class="text-xs text-slate-400 font-normal">/bln</span></div>
                    </div>
                </div>

                <div class="p-4 pt-0 flex gap-2">
                    <form action="<?php echo e(route('user.wishlist.remove', $w->id)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 rounded-xl text-xs font-bold transition">
                            Hapus dari Favorit
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 p-12 text-center bg-white rounded-3xl border border-slate-200 text-xs text-slate-400 space-y-2">
                <span class="text-3xl block">❤️</span>
                <strong>Belum ada hunian yang tersimpan di favorit.</strong>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/user/wishlist.blade.php ENDPATH**/ ?>