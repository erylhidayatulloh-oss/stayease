@extends('layouts.dashboard')

@section('title', 'Ulasan & Tanggapan Penyewa')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Ulasan & Rating dari Penyewa</h1>
        <p class="text-xs text-slate-500">Baca masukan penghuni dan berikan tanggapan resmi pengelola untuk meningkatkan reputasi listing.</p>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $rev)
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ $rev->user->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80' }}" alt="{{ $rev->user->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="font-extrabold text-xs text-slate-900">{{ $rev->user->name }}</h4>
                            <p class="text-[10px] text-slate-400">Unit: {{ $rev->property->title }} • {{ $rev->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="text-amber-500 font-black text-sm flex items-center gap-1">
                        <span>★</span> {{ $rev->rating }}.0
                    </div>
                </div>

                <p class="text-xs text-slate-700 leading-relaxed italic">
                    "{{ $rev->comment }}"
                </p>

                <!-- Owner Reply Section -->
                @if($rev->owner_reply)
                    <div class="p-3 bg-emerald-50/50 rounded-2xl border border-emerald-200 text-xs space-y-1">
                        <div class="font-bold text-[11px] text-emerald-800">Tanggapan Anda (Pengelola):</div>
                        <p class="text-slate-700">{{ $rev->owner_reply }}</p>
                    </div>
                @else
                    <form action="{{ route('mitra.reviews.reply', $rev->id) }}" method="POST" class="pt-2 border-t border-slate-100 flex gap-2 text-xs">
                        @csrf
                        <input type="text" name="reply" placeholder="Tulis tanggapan terima kasih kepada penyewa..." required class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition">
                            Balas
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 text-xs text-slate-400">
                Belum ada ulasan yang masuk untuk properti Anda.
            </div>
        @endforelse
    </div>

</div>
@endsection
