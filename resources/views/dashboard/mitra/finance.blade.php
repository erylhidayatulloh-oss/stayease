@extends('layouts.dashboard')

@section('title', 'Keuangan & Pencairan Omzet')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Keuangan & Pencairan Omzet</h1>
        <p class="text-xs text-slate-500">Laporan pendapatan bersih setelah potongan biaya platform 5% dan riwayat transfer dana ke rekening bank.</p>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <div class="bg-gradient-to-br from-emerald-900 to-slate-900 text-white p-6 rounded-3xl shadow-lg space-y-4">
            <div>
                <span class="text-xs text-emerald-300 font-bold uppercase tracking-wider block">Saldo Siap Dicairkan (Net 95%)</span>
                <div class="text-3xl font-black text-emerald-400 mt-1">
                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                </div>
            </div>

            <!-- Request Payout Trigger Form -->
            @if($availableBalance >= 100000)
                <form action="{{ route('mitra.finance.request_payout') }}" method="POST" class="space-y-3 pt-2 border-t border-emerald-800/60 text-xs">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $availableBalance }}">
                    <input type="hidden" name="bank_name" value="{{ $mitra->bank_name ?? 'Bank BCA' }}">
                    <input type="hidden" name="account_number" value="{{ $mitra->bank_account_number ?? '5210987654' }}">
                    <input type="hidden" name="account_holder" value="{{ $mitra->bank_account_holder ?? $mitra->name }}">

                    <div class="text-[11px] text-slate-300">
                        Transfer ke: <strong>{{ $mitra->bank_name ?? 'BCA' }} - {{ $mitra->bank_account_number ?? '5210987654' }}</strong>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl shadow transition">
                        ⚡ Tarik Semua Saldo ke Rekening Bank
                    </button>
                </form>
            @else
                <div class="text-[11px] text-slate-400">Saldo minimum untuk penarikan adalah Rp 100.000.</div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 flex flex-col justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase">Total Omzet Bruto</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-xs text-slate-500">
                Fee Platform (5%): <strong class="text-rose-600">-Rp {{ number_format($platformFeeTotal, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-2 flex flex-col justify-between">
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase">Total Telah Dicairkan</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-xs text-emerald-700 font-bold">
                ● Seluruh pencairan berhasil ditransfer
            </div>
        </div>

    </div>

    <!-- Payout History Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 font-extrabold text-sm text-slate-900">
            Riwayat Penarikan Dana & Pencairan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4">Nominal Penarikan</th>
                        <th class="p-4">Rekening Tujuan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($payouts as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-700">{{ $p->created_at->format('d M Y, H:i') }} WIB</td>
                            <td class="p-4 font-black text-slate-900">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $p->bank_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $p->account_number }} a/n {{ $p->account_holder }}</div>
                            </td>
                            <td class="p-4">
                                @if($p->status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">DITRANSFER (LUNAS)</span>
                                @elseif($p->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-full">PROSES ADMIN</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-[10px] font-black rounded-full">DITOLAK</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">{{ $p->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada riwayat penarikan dana.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
