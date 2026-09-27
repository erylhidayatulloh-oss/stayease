<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Mitra | Stayease</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-slate-800">

<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950">
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl p-8 sm:p-10">

        <div class="mb-6 text-center">
            <div class="flex items-center justify-center gap-2.5 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-500/20">S</div>
                <div class="font-extrabold text-xl text-slate-900">Stay<span class="text-emerald-600">ease</span></div>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Daftar Jadi Mitra Stayease</h1>
            <p class="text-xs text-slate-500 mt-1">Punya kost, kontrakan, atau apartemen? Sewakan lewat Stayease & dapatkan penyewa lebih cepat.</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-5 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-semibold">
                <ul class="list-disc list-inside space-y-0.5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('mitra.register.submit')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Tier selection -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700">Pilih Jenis Kemitraan *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <label class="tier-card relative flex flex-col p-5 border-2 border-slate-200 rounded-2xl cursor-pointer transition hover:border-emerald-300">
                        <input type="radio" name="mitra_tier" value="reguler" class="peer sr-only" checked <?php echo e(old('mitra_tier', 'reguler') === 'reguler' ? 'checked' : ''); ?>>
                        <span class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-emerald-500 peer-checked:bg-emerald-50/60 pointer-events-none transition"></span>
                        <span class="relative flex items-center justify-between mb-2">
                            <span class="text-2xl">🏠</span>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-black rounded-full">Potongan 5%</span>
                        </span>
                        <span class="relative font-extrabold text-sm text-slate-900">Mitra Biasa</span>
                        <span class="relative text-xs text-slate-500 mt-1 leading-relaxed">
                            "Numpang" di platform Stayease — Anda upload listing sendiri, atur harga, dan kelola pembayaran &amp; penyewa langsung dari dashboard Anda.
                        </span>
                    </label>

                    <label class="tier-card relative flex flex-col p-5 border-2 border-slate-200 rounded-2xl cursor-pointer transition hover:border-amber-300">
                        <input type="radio" name="mitra_tier" value="pro" class="peer sr-only" <?php echo e(old('mitra_tier') === 'pro' ? 'checked' : ''); ?>>
                        <span class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-amber-500 peer-checked:bg-amber-50/60 pointer-events-none transition"></span>
                        <span class="relative flex items-center justify-between mb-2">
                            <span class="text-2xl">🌟</span>
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">Potongan 15%</span>
                        </span>
                        <span class="relative font-extrabold text-sm text-slate-900">Mitra Pro</span>
                        <span class="relative text-xs text-slate-500 mt-1 leading-relaxed">
                            Semua di-handle tim Admin Stayease — upload listing, verifikasi calon penyewa, sampai urus pembayaran. Anda tinggal terima laporan &amp; payout.
                        </span>
                    </label>

                </div>
            </div>

            <!-- Account fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        placeholder="Nama pemilik / penanggung jawab"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp *</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                    placeholder="nama@email.com"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi *</label>
                    <input type="password" name="password" required minlength="6"
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Kata Sandi *</label>
                    <input type="password" name="password_confirmation" required minlength="6"
                        placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition">
                Daftar Sebagai Mitra
            </button>
        </form>

        <div class="mt-6 text-center space-y-1">
            <p class="text-xs text-slate-500">Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="font-bold text-emerald-700 hover:underline">Masuk di sini</a></p>
            <a href="<?php echo e(route('home')); ?>" class="block text-xs font-bold text-slate-400 hover:text-slate-700">&larr; Kembali ke Situs Utama</a>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi 2/resources/views/auth/register_mitra.blade.php ENDPATH**/ ?>