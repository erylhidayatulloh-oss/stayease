<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} | Stayease</title>
    @if($article->excerpt)
        <meta name="description" content="{{ $article->excerpt }}">
    @endif

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

    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-base shadow-md shadow-emerald-500/20">S</div>
                <div class="font-extrabold text-slate-900 text-base">Stay<span class="text-emerald-600">ease</span></div>
            </a>
            <a href="{{ route('articles.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Semua Artikel</a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-10 sm:py-14">
        <article class="space-y-6">
            <div class="space-y-3">
                @if($article->status !== 'published')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">📝 Pratinjau Draft (belum terbit publik)</span>
                @endif
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight">{{ $article->title }}</h1>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold">
                    <span>{{ $article->author->name ?? 'Tim Stayease' }}</span>
                    <span>&middot;</span>
                    <span>{{ ($article->published_at ?? $article->created_at)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            @if($article->cover_image)
                <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" class="w-full h-64 sm:h-96 object-cover rounded-3xl">
            @endif

            <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed whitespace-pre-line">{{ $article->content }}</div>
        </article>

        @if($related->isNotEmpty())
            <div class="mt-14 pt-8 border-t border-slate-200 space-y-4">
                <h3 class="font-extrabold text-slate-900">Artikel Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($related as $r)
                        <a href="{{ route('articles.show', $r->slug) }}" class="block bg-white rounded-2xl p-4 border border-slate-200 hover:shadow-md transition">
                            <h4 class="font-bold text-xs text-slate-900 line-clamp-2">{{ $r->title }}</h4>
                            <span class="text-[10px] text-slate-400">{{ $r->published_at?->translatedFormat('d M Y') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

</body>
</html>
