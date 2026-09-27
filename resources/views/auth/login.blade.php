<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun | Stayease</title>

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
                <h1 class="text-2xl font-black leading-tight mb-3">Satu akun, akses sesuai peran Anda.</h1>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Masuk dengan email &amp; kata sandi Anda — sistem akan otomatis mengarahkan ke dashboard sesuai peran akun Anda (Penyewa, Mitra, Admin, atau Superadmin).
                </p>
            </div>
            <div class="space-y-3 text-xs text-slate-400">
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Autentikasi email &amp; kata sandi sungguhan</div>
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Setiap role hanya melihat dashboard miliknya</div>
                <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Akun tersuspend/diblokir otomatis ditolak</div>
            </div>
        </div>

        <!-- Right: Login form -->
        <div class="bg-white p-8 sm:p-10">

            <div class="mb-6">
                <h2 class="text-xl font-black text-slate-900">Masuk ke Dashboard</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan email &amp; kata sandi akun Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                @if ($intended)
                    <input type="hidden" name="intended" value="{{ $intended }}">
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="nama@email.com"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-600 font-semibold">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit"
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center space-y-2">
                <p class="text-xs text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('user.register') }}" class="font-bold text-emerald-700 hover:underline">Daftar Sebagai Penyewa</a>
                </p>
                <p class="text-xs text-slate-500">
                    Punya properti untuk disewakan?
                    <a href="{{ route('mitra.register') }}" class="font-bold text-emerald-700 hover:underline">Daftar Jadi Mitra</a>
                </p>
                <a href="{{ route('home') }}" class="block text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Kembali ke Situs Utama</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
