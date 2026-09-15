<?php $__env->startSection('title', 'Pesan Penyewa'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-black text-slate-900">Pesan Masuk dari Calon Penyewa</h1>
        <p class="text-xs text-slate-500">Balas pertanyaan calon penyewa yang tertarik dengan properti Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Conversation List (3 cols) -->
        <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-200 shadow-sm p-3 space-y-1.5 h-fit">
            <p class="text-[10px] font-black text-slate-400 uppercase px-2 pt-1 pb-2">Percakapan</p>
            <?php $__empty_1 = true; $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('mitra.messages', ['with' => $partner->id])); ?>"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo e($activePartnerId === $partner->id ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50'); ?>">
                    <span><?php echo e($partner->name); ?></span>
                    <?php if(($unreadCounts[$partner->id] ?? 0) > 0): ?>
                        <span class="w-5 h-5 flex items-center justify-center bg-rose-500 text-white text-[10px] rounded-full"><?php echo e($unreadCounts[$partner->id]); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-[11px] text-slate-400 px-2 py-3">Belum ada calon penyewa yang menghubungi Anda.</p>
            <?php endif; ?>
        </div>

        <!-- Chat Stream (9 cols) -->
        <div class="lg:col-span-9 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col h-[520px]">
            <div class="p-4 bg-slate-900 text-white rounded-t-3xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                    <div>
                        <div class="font-bold text-xs">
                            <?php if($activePartnerId && $partners->firstWhere('id', $activePartnerId)): ?>
                                Chat dengan <?php echo e($partners->firstWhere('id', $activePartnerId)->name); ?>

                            <?php else: ?>
                                Pusat Pesan
                            <?php endif; ?>
                        </div>
                        <div class="text-[10px] text-slate-400">Terkoneksi aman dengan enkripsi Stayease</div>
                    </div>
                </div>
            </div>

            <!-- Messages Box -->
            <div id="chat-messages-box" class="p-4 flex-1 overflow-y-auto space-y-3 bg-slate-50 text-xs">
                <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex flex-col <?php echo e($m->sender_id === auth()->id() ? 'items-end' : 'items-start'); ?>">
                        <div class="p-3 rounded-2xl max-w-sm <?php echo e($m->sender_id === auth()->id() ? 'bg-emerald-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none shadow-sm'); ?>">
                            <?php echo e($m->content); ?>

                        </div>
                        <span class="text-[10px] text-slate-400 mt-0.5 px-1">
                            <?php echo e($m->created_at->format('H:i')); ?>

                            <?php if($m->property): ?>&bull; soal <?php echo e($m->property->title); ?><?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 text-slate-400">
                        <?php if($activePartnerId): ?>
                            Belum ada percakapan dengan penyewa ini.
                        <?php else: ?>
                            Pilih percakapan di samping untuk mulai membalas.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Send Form -->
            <form action="<?php echo e(route('mitra.messages.send')); ?>" method="POST" class="p-3 bg-white border-t border-slate-200 flex gap-2 rounded-b-3xl">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="recipient_id" value="<?php echo e($activePartnerId); ?>">
                <input type="text" name="content" placeholder="Balas pesan penyewa..." required <?php echo e($activePartnerId ? '' : 'disabled'); ?> class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500 disabled:opacity-50">
                <button type="submit" <?php echo e($activePartnerId ? '' : 'disabled'); ?> class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow transition">
                    Kirim
                </button>
            </form>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var box = document.getElementById('chat-messages-box');
        if (box) box.scrollTop = box.scrollHeight;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/mitra/messages.blade.php ENDPATH**/ ?>