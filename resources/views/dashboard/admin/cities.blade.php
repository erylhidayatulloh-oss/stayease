@extends('layouts.dashboard')

@section('title', 'Manajemen SEO City Hubs')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Manajemen Wilayah & SEO City Hubs</h1>
        <p class="text-xs text-slate-500">Kelola direktori kota metropolitan, kampus terdekat, dan struktur metadata SEO Schema.org.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cities as $c)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm space-y-3">
                <div class="relative h-36">
                    <img src="{{ $c->image }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 text-white">
                        <span class="text-[10px] uppercase font-bold text-emerald-400">{{ $c->province }}</span>
                        <h3 class="font-extrabold text-base leading-tight">{{ $c->name }}</h3>
                    </div>
                </div>

                <div class="p-4 pt-0 space-y-2 text-xs">
                    <p class="text-slate-600 line-clamp-2">{{ $c->description }}</p>
                    
                    <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2">
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <span class="text-[10px] text-slate-400 block font-bold">Rata2 Kost</span>
                            <strong class="text-slate-800">Rp {{ number_format($c->avg_kost_price, 0, ',', '.') }}</strong>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <span class="text-[10px] text-slate-400 block font-bold">Listing Aktif</span>
                            <strong class="text-emerald-700">{{ $c->properties_count }} Properti</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
