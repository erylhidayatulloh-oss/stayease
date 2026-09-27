@extends('layouts.dashboard')

@section('title', 'Beranda Penyewa')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Header & Trust Badge -->
    <div class="bg-gradient-to-r from-emerald-900 via-slate-900 to-teal-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-full text-xs font-bold">
                <span>🛡️ Akun Penyewa Terverifikasi Resmi</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ $user->name }}! 👋</h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Pantau status sewa aktif, surat perjanjian sewa digital, pembayaran QRIS/VA, dan komunikasi langsung dengan pengelola properti.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('user.bookings') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl shadow transition">
                Lihat Kontrak & Sewa Saya
            </a>
        </div>
    </div>

    <!-- Active Lease Highlight Card -->
    @if($activeBookings->count() > 0)
        @php $active = $activeBookings->first(); @endphp
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🏡</span>
                    <h3 class="font-extrabold text-sm text-slate-900">Hunian Sewa Aktif Anda</h3>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-black rounded-full">
                    STATUS: {{ strtoupper(str_replace('_', ' ', $active->status)) }}
                </span>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <img src="{{ $active->property->images[0] ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80' }}" 
                     alt="{{ $active->property->title }}" 
                     class="w-full sm:w-44 h-32 object-cover rounded-2xl shadow-sm">
                
                <div class="space-y-2 flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                        Kode Booking: {{ $active->booking_code }}
                    </span>
                    <h4 class="font-extrabold text-base text-slate-900 truncate">{{ $active->property->title }}</h4>
                    <p class="text-xs text-slate-500">{{ $active->unit->name }} • {{ $active->property->sub_district }}, {{ $active->property->city->name ?? 'Indonesia' }}</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 text-xs">
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <span class="text-[10px] text-slate-400 block">Tanggal Masuk</span>
                            <strong class="text-slate-800">{{ $active->check_in_date->format('d M Y') }}</strong>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <span class="text-[10px] text-slate-400 block">Durasi Sewa</span>
                            <strong class="text-slate-800">{{ $active->duration_months }} Bulan</strong>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl col-span-2 sm:col-span-1">
                            <span class="text-[10px] text-slate-400 block">Total Biaya</span>
                            <strong class="text-emerald-700 font-black">Rp {{ number_format($active->grand_total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 w-full sm:w-auto shrink-0">
                    <a href="{{ route('user.contract', $active->id) }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center shadow-sm flex items-center justify-center gap-1.5 transition">
                        <span>📜 Surat Perjanjian Sewa</span>
                    </a>
                    <a href="{{ route('user.messages') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl text-center flex items-center justify-center gap-1.5 transition">
                        <span>💬 Chat Pengelola</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0 font-bold">
                📑
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold">Total Transaksi Sewa</span>
                <div class="text-2xl font-black text-slate-900">{{ $activeBookings->count() + $completedBookingsCount }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl shrink-0 font-bold">
                ❤️
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold">Favorit Tersimpan</span>
                <div class="text-2xl font-black text-slate-900">{{ $wishlists->count() }} Properti</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl shrink-0 font-bold">
                💬
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold">Pesan Masuk</span>
                <div class="text-2xl font-black text-slate-900">{{ $unreadMessagesCount }} Belum Dibaca</div>
            </div>
        </div>
    </div>

    <!-- Wishlist Preview -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-base text-slate-900">Hunian Favorit Anda</h3>
            <a href="{{ route('user.wishlist') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($wishlists as $w)
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <img src="{{ $w->property->images[0] ?? '' }}" alt="{{ $w->property->title }}" class="w-full h-32 object-cover">
                        <div class="p-3 space-y-1">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 bg-slate-100 rounded text-slate-700">{{ strtoupper($w->property->property_type) }}</span>
                            <h4 class="font-bold text-xs text-slate-900 truncate">{{ $w->property->title }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $w->property->sub_district }}</p>
                            <div class="text-xs font-black text-emerald-700">Rp {{ number_format($w->property->base_price_monthly, 0, ',', '.') }}/bln</div>
                        </div>
                    </div>
                    <div class="p-3 pt-0 flex gap-2">
                        <form action="{{ route('user.wishlist.remove', $w->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 rounded-xl text-xs font-bold transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-4 p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-400">
                    Belum ada hunian favorit yang tersimpan.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
