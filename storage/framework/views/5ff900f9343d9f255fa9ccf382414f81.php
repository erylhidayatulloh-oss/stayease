<?php $__env->startSection('title', 'Kelola Artikel'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Kelola Artikel</h1>
            <p class="text-xs text-slate-500">Tulis & terbitkan artikel untuk halaman <a href="<?php echo e(route('articles.index')); ?>" target="_blank" class="underline font-bold">/artikel</a> di situs utama.</p>
        </div>

        <a href="<?php echo e(route('admin.articles.create')); ?>" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1.5 transition">
            <span>➕ Tulis Artikel Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Artikel</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Diperbarui</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-900 line-clamp-1"><?php echo e($a->title); ?></div>
                                <div class="text-[11px] text-slate-400">/artikel/<?php echo e($a->slug); ?></div>
                            </td>
                            <td class="p-4 text-slate-600"><?php echo e($a->author->name ?? '-'); ?></td>
                            <td class="p-4">
                                <?php if($a->status === 'published'): ?>
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">✓ Terbit</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]"><?php echo e($a->updated_at->diffForHumans()); ?></td>
                            <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.articles.edit', $a->slug)); ?>" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-bold text-[10px] transition">
                                    Edit
                                </a>
                                <form action="<?php echo e(route('admin.articles.destroy', $a->slug)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-[10px] transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada artikel. Klik "Tulis Artikel Baru" untuk memulai.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($articles->hasPages()): ?>
            <div class="p-4 border-t border-slate-100">
                <?php echo e($articles->links()); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi 2/resources/views/dashboard/admin/articles.blade.php ENDPATH**/ ?>