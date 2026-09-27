@extends('layouts.dashboard')

@section('title', $article ? 'Edit Artikel' : 'Tulis Artikel Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ $article ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h1>
            <p class="text-xs text-slate-500">Artikel berstatus "Terbit" akan langsung tampil di halaman publik <a href="{{ route('articles.index') }}" target="_blank" class="underline font-bold">/artikel</a>.</p>
        </div>
        <a href="{{ route('admin.articles') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Kembali</a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 font-semibold">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $article ? route('admin.articles.update', $article->slug) : route('admin.articles.store') }}"
          method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5 text-xs">
        @csrf
        @if($article)
            @method('PATCH')
        @endif

        <div class="space-y-1">
            <label class="block font-bold text-slate-700">Judul Artikel *</label>
            <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required
                placeholder="Cth: 5 Tips Memilih Kost Dekat Kampus"
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
        </div>

        <div class="space-y-1">
            <label class="block font-bold text-slate-700">URL Gambar Sampul (opsional)</label>
            <input type="url" name="cover_image" value="{{ old('cover_image', $article->cover_image ?? '') }}"
                placeholder="https://images.unsplash.com/..."
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500">
        </div>

        <div class="space-y-1">
            <label class="block font-bold text-slate-700">Ringkasan Singkat (opsional)</label>
            <textarea name="excerpt" rows="2" placeholder="Ringkasan 1-2 kalimat untuk kartu artikel & SEO..."
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
        </div>

        <div class="space-y-1">
            <label class="block font-bold text-slate-700">Isi Artikel *</label>
            <textarea name="content" rows="12" required placeholder="Tulis isi artikel di sini..."
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500">{{ old('content', $article->content ?? '') }}</textarea>
        </div>

        <div class="space-y-1">
            <label class="block font-bold text-slate-700">Status Publikasi *</label>
            <select name="status" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                <option value="draft" {{ old('status', $article->status ?? 'draft') === 'draft' ? 'selected' : '' }}>📝 Draft (belum tampil publik)</option>
                <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>✅ Terbit (tampil di /artikel)</option>
            </select>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow transition">
                {{ $article ? 'Simpan Perubahan' : 'Simpan Artikel' }}
            </button>
        </div>
    </form>

</div>
@endsection
