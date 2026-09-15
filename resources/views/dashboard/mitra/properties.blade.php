@extends('layouts.dashboard')

@section('title', 'Kelola Properti & Kamar')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Properti & Inventori Kamar</h1>
            <p class="text-xs text-slate-500">Kelola informasi harga, ketersediaan unit, dan fasilitas listing hunian Anda.</p>
        </div>

        <a href="{{ route('mitra.properties.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1.5 transition">
            <span>➕ Tambah Listing Baru</span>
        </a>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($properties as $p)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="relative h-44 bg-slate-100">
                        <img src="{{ $p->images[0] ?? '' }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($p->status === 'active')
                                <span class="px-2.5 py-0.5 bg-emerald-600 text-white text-[10px] font-black rounded-full shadow">TAYANG (ACTIVE)</span>
                            @elseif($p->status === 'pending_review')
                                <span class="px-2.5 py-0.5 bg-amber-500 text-slate-950 text-[10px] font-black rounded-full shadow">MENUNGGU VERIFIKASI ADMIN</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded-full shadow">{{ strtoupper($p->status) }}</span>
                            @endif

                            @if($p->verified_official)
                                <span class="px-2 py-0.5 bg-slate-900 text-emerald-300 text-[10px] font-bold rounded-full">🛡️ Official Stayease</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 space-y-2">
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 rounded text-slate-700">{{ strtoupper(str_replace('_', ' ', $p->property_type)) }}</span>
                        <h3 class="font-extrabold text-sm text-slate-900 line-clamp-1">{{ $p->title }}</h3>
                        <p class="text-xs text-slate-500">{{ $p->address }}, {{ $p->city->name ?? '' }}</p>
                        
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Harga Dasar:</span>
                            <span class="font-black text-slate-900">Rp {{ number_format($p->base_price_monthly, 0, ',', '.') }}/bln</span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Total Kamar:</span>
                            <span class="font-bold text-emerald-700">{{ $p->units->sum('total_count') }} Unit ({{ $p->units->sum('available_count') }} Tersedia)</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between gap-2 mt-2">
                    <span class="text-[11px] text-slate-400">Rating: ★ {{ $p->rating_avg }} ({{ $p->reviews_count }})</span>
                    
                    <form action="{{ route('mitra.properties.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus properti ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white rounded-3xl border border-slate-200 text-xs text-slate-400">
                Belum ada properti terdaftar. Klik "Tambah Listing Baru" untuk mulai memasang iklan sewa.
            </div>
        @endforelse
    </div>

</div>
@endsection
