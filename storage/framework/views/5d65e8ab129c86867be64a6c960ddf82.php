<?php $__env->startSection('title', 'Profil & Verifikasi KTP'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Data Profil & Verifikasi KTP</h1>
        <p class="text-xs text-slate-500">Kelola identitas resmi Anda untuk kemudahan pembuatan kontrak sewa digital legal.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        
        <!-- Verification Badge -->
        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-3 text-xs text-emerald-900">
            <span class="text-2xl">🛡️</span>
            <div>
                <strong>Status KYC: Terverifikasi Digital (e-KYC Valid)</strong>
                <p class="text-[11px] text-emerald-700">Data identitas Anda telah diverifikasi sistem Stayease pada <?php echo e($user->ktp_verified_at ? $user->ktp_verified_at->format('d M Y') : 'Hari ini'); ?>.</p>
            </div>
        </div>

        <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" class="space-y-4 text-xs">
            <?php echo csrf_field(); ?>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Lengkap Sesuai KTP *</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Alamat Email (Akun)</label>
                <input type="email" value="<?php echo e($user->email); ?>" disabled class="w-full p-3 bg-slate-100 border border-slate-200 rounded-xl font-mono text-slate-500 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nomor WhatsApp Aktif *</label>
                <input type="tel" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nomor Induk Kependudukan (NIK KTP) *</label>
                <input type="text" name="ktp_number" value="<?php echo e(old('ktp_number', $user->ktp_number)); ?>" required maxlength="16" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow transition">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/user/profile.blade.php ENDPATH**/ ?>