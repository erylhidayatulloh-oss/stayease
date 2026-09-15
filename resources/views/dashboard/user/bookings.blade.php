@extends('layouts.dashboard')

@section('title', 'Sewa & Kontrak Saya')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Riwayat Pemesanan & Sewa Hunian</h1>
            <p class="text-xs text-slate-500">Kelola status sewa aktif, surat perjanjian resmi, dan bukti pembayaran.</p>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Kode Booking</th>
                        <th class="p-4">Properti & Kamar</th>
                        <th class="p-4">Jadwal Masuk</th>
                        <th class="p-4">Durasi</th>
                        <th class="p-4">Total Biaya</th>
                        <th class="p-4">Status Sewa</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-emerald-700">
                                {{ $b->booking_code }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $b->property->title }}</div>
                                <div class="text-[11px] text-slate-400">{{ $b->unit->name }} • {{ $b->property->city->name ?? '' }}</div>
                            </td>
                            <td class="p-4 text-slate-700">
                                {{ $b->check_in_date->format('d M Y') }}
                                <span class="block text-[10px] text-slate-400">s/d {{ $b->check_in_date->copy()->addMonths($b->duration_months)->format('d M Y') }}</span>
                            </td>
                            <td class="p-4 text-slate-700">
                                {{ $b->duration_months }} Bulan
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                Rp {{ number_format($b->grand_total, 0, ',', '.') }}
                                <span class="text-[10px] block text-slate-400 font-normal uppercase">{{ $b->payment_method }} ({{ $b->payment_status }})</span>
                            </td>
                            <td class="p-4">
                                @if($b->status === 'active_lease')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">AKTIF TINGGAL</span>
                                @elseif($b->status === 'paid')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-full">LUNAS (SIAP MASUK)</span>
                                @elseif($b->status === 'pending_payment')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">MENUNGGU BAYAR</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-black rounded-full">{{ strtoupper($b->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-1">
                                <a href="{{ route('user.contract', $b->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-[11px] inline-flex items-center gap-1 transition">
                                    <span>📜 Kontrak</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 text-xs">
                                Anda belum memiliki riwayat pemesanan sewa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
