<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel | Stayease</title>

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
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50">

    <!-- Simple top bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-base shadow-md shadow-emerald-500/20">S</div>
                <div class="font-extrabold text-slate-900 text-base">Stay<span class="text-emerald-600">ease</span></div>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Kembali ke Situs Utama</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10 sm:py-14 space-y-8">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">📰 Artikel Stayease</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Tips & Info Seputar Sewa Hunian</h1>
            <p class="text-sm text-slate-500 max-w-2xl">Panduan, tips, dan info terbaru seputar kost, apartemen, dan sewa hunian dari tim Stayease.</p>
        </div>

        @if($articles->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 text-sm text-slate-400">
                Belum ada artikel yang diterbitkan. Nantikan artikel terbaru dari kami!
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($articles as $a)
                    <a href="{{ route('articles.show', $a->slug) }}" class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col">
                        <div class="h-40 bg-slate-100">
                            <img src="{{ $a->cover_image ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $a->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 space-y-2 flex-1 flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400">{{ $a->published_at?->translatedFormat('d M Y') }}</span>
                            <h3 class="font-extrabold text-sm text-slate-900 line-clamp-2">{{ $a->title }}</h3>
                            @if($a->excerpt)
                                <p class="text-xs text-slate-500 line-clamp-3 flex-1">{{ $a->excerpt }}</p>
                            @endif
                            <span class="text-[11px] font-bold text-emerald-700 mt-auto">Baca Selengkapnya &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($articles->hasPages())
                <div>{{ $articles->links() }}</div>
            @endif
        @endif
    </main>

</body>
</html>
