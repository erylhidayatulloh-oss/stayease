@extends('layouts.dashboard')

@section('title', 'Manajemen Transaksi & Pembayaran')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Manajemen Transaksi & Escrow Pembayaran</h1>
        <p class="text-xs text-slate-500">Monitor status pembayaran QRIS, Virtual Account bank, dan eksekusi pengembalian deposit jaminan (refund).</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Kode Transaksi</th>
                        <th class="p-4">Penyewa</th>
                        <th class="p-4">Properti</th>
                        <th class="p-4">Total & Metode</th>
                        <th class="p-4">Status Bayar</th>
                        <th class="p-4">Status Sewa</th>
                        <th class="p-4 text-right">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-bold text-emerald-700">
                                {{ $b->booking_code }}
                                <span class="text-[10px] block text-slate-400 font-normal">{{ $b->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $b->tenant_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $b->tenant_phone }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $b->property->title }}</div>
                                <div class="text-[10px] text-slate-400">{{ $b->unit->name }} • {{ $b->duration_months }} Bln</div>
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                Rp {{ number_format($b->grand_total, 0, ',', '.') }}
                                <span class="text-[10px] block text-slate-400 font-mono uppercase">{{ $b->payment_method }}</span>
                            </td>
                            <td class="p-4">
                                @if($b->payment_status === 'verified')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">LUNAS VERIFIED</span>
                                @elseif($b->payment_status === 'unpaid')
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">BELUM BAYAR</span>
                                @elseif($b->payment_status === 'refunded')
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-black rounded-full">DEPOSIT DIKEMBALIKAN</span>
                                @else
                                    <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-black rounded-full">{{ strtoupper($b->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-slate-700">
                                {{ strtoupper(str_replace('_', ' ', $b->status)) }}
                            </td>
                            <td class="p-4 text-right space-x-1">
                                @if($b->payment_status === 'unpaid')
                                    <form action="{{ route('admin.bookings.update_payment', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="verified">
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[10px]">
                                            ✓ Set Lunas
                                        </button>
                                    </form>
                                @elseif($b->status === 'completed' && $b->payment_status === 'verified')
                                    <form action="{{ route('admin.bookings.update_payment', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="refunded">
                                        <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-[10px]">
                                            Kembalikan Deposit
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-[10px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
