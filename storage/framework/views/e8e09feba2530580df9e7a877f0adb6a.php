<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Stayease</title>

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
    <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl">

        <!-- Left: Branding panel -->
        <div class="hidden lg:flex flex-col justify-between bg-slate-900 text-white p-10">
            <div>
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-500/20">S</div>
                    <div class="font-extrabold text-xl">Stay<span class="text-emerald-400">ease</span></div>
                </div>
                <h1 class="text-2xl font-black leading-tight mb-3">Cari kost & apartemen jadi lebih mudah.</h1>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Daftar sebagai penyewa untuk menyimpan favorit, chat langsung dengan pemilik, dan booking dengan pembayaran digital yang aman.
                </p>
            </div>
            <div class="space-y-3 text-xs text-slate-400">
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Simpan hunian favorit & bandingkan pilihan</div>
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Booking & bayar online tanpa ribet</div>
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Riwayat sewa & kontrak digital tersimpan rapi</div>
            </div>
        </div>

        <!-- Right: Register form -->
        <div class="bg-white p-8 sm:p-10">

            <div class="mb-6">
                <h2 class="text-xl font-black text-slate-900">Daftar Akun Penyewa</h2>
                <p class="text-xs text-slate-500 mt-1">Gratis, cuma butuh 1 menit.</p>
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

            <form method="POST" action="<?php echo e(route('user.register.submit')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        placeholder="Nama Anda"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                        placeholder="nama@email.com"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" required minlength="6"
                            placeholder="Min. 6 karakter"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Sandi</label>
                        <input type="password" name="password_confirmation" required minlength="6"
                            placeholder="Ulangi sandi"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center space-y-1">
                <p class="text-xs text-slate-500">Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="font-bold text-emerald-700 hover:underline">Masuk di sini</a></p>
                <p class="text-xs text-slate-500">Punya properti untuk disewakan? <a href="<?php echo e(route('mitra.register')); ?>" class="font-bold text-emerald-700 hover:underline">Daftar Jadi Mitra</a></p>
                <a href="<?php echo e(route('home')); ?>" class="block text-xs font-bold text-slate-400 hover:text-slate-700">&larr; Kembali ke Situs Utama</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi 2/resources/views/auth/register_user.blade.php ENDPATH**/ ?>