@extends('layouts.dashboard')

@section('title', 'Manajemen Promo & Voucher')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Kupon & Promo Diskon</h1>
            <p class="text-xs text-slate-500">Buat kampanye diskon sewa, cashback mahasiswa, dan voucher potongan bulanan.</p>
        </div>
    </div>

    <!-- Create Promo Form Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-extrabold text-sm text-slate-900">Tambah Kupon Promo Baru</h3>
        
        <form action="{{ route('admin.promos.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            @csrf
            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Kode Voucher *</label>
                <input type="text" name="code" placeholder="Cth: STAYEASE2026" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl uppercase font-mono font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Judul Promo *</label>
                <input type="text" name="title" placeholder="Diskon Awal Tahun" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Tipe Potongan *</label>
                <select name="discount_type" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                    <option value="percentage">Persentase (%)</option>
                    <option value="fixed">Nominal Tetap (Rp)</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nilai Potongan *</label>
                <input type="number" name="discount_value" placeholder="10 (untuk 10%)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Batas Maks. Diskon (Rp)</label>
                <input type="number" name="max_discount" placeholder="500000" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Min. Transaksi (Rp)</label>
                <input type="number" name="min_transaction" value="1000000" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Berlaku Sampai *</label>
                <input type="date" name="valid_until" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow transition">
                    + Terbitkan Voucher
                </button>
            </div>
        </form>
    </div>

    <!-- Active Promos Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Kode Promo</th>
                        <th class="p-4">Judul & Badge</th>
                        <th class="p-4">Besaran Diskon</th>
                        <th class="p-4">Penggunaan Kuota</th>
                        <th class="p-4">Berlaku Hingga</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($promos as $promo)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 font-mono font-black text-emerald-700 text-sm">{{ $promo->code }}</td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $promo->title }}</div>
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] text-slate-600">{{ $promo->badge ?? 'PROMO' }}</span>
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                @if($promo->discount_type === 'percentage')
                                    {{ $promo->discount_value }}% (Maks. Rp {{ number_format($promo->max_discount ?? 0, 0, ',', '.') }})
                                @else
                                    Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-4 text-slate-700">
                                {{ $promo->used_count }} / {{ $promo->usage_limit }} Digunakan
                            </td>
                            <td class="p-4 text-slate-700">{{ $promo->valid_until->format('d M Y') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">AKTIF</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada promo yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
