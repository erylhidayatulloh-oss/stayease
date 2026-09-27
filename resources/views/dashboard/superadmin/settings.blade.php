@extends('layouts.dashboard')

@section('title', 'Konfigurasi Global Platform')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Konfigurasi Global Platform Stayease</h1>
        <p class="text-xs text-slate-500">Atur parameter komisi platform, batasan deposit escrow, integrasi payment gateway, dan mode pemeliharaan.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6 text-xs">
        
        <div class="space-y-4">
            <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">1. Skema Komisi & Keuangan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Komisi Platform Stayease (%)</label>
                    <input type="number" value="5" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono">
                    <span class="text-[10px] text-slate-400">Dipotong otomatis dari total transaksi sewa Mitra.</span>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Biaya Layanan Penyewa Standar (Rp)</label>
                    <input type="number" value="50000" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono">
                    <span class="text-[10px] text-slate-400">Biaya pemeliharaan platform per transaksi booking.</span>
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">2. Gateway Pembayaran & Escrow</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div>
                        <div class="font-bold text-slate-900">Integrasi QRIS Dynamic (Bank Indonesia / ASPI)</div>
                        <div class="text-[10px] text-slate-400">Status: Terkoneksi (Sandbox / Mock Simulator Aktif)</div>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">AKTIF</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div>
                        <div class="font-bold text-slate-900">Virtual Account Multi-Bank (BCA, Mandiri, BNI, BRI)</div>
                        <div class="text-[10px] text-slate-400">Status: Terkoneksi API Midtrans / Xendit Sandbox</div>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">AKTIF</span>
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">3. Keamanan & Pemeliharaan</h3>

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200">
                <div>
                    <div class="font-bold text-slate-900">Mode Pemeliharaan (Maintenance Mode)</div>
                    <div class="text-[10px] text-slate-400">Hanya Superadmin yang dapat mengakses website ketika aktif.</div>
                </div>
                <span class="px-2.5 py-1 bg-slate-200 text-slate-700 text-[10px] font-bold rounded-full">NONAKTIF</span>
            </div>
        </div>

        <div class="pt-4">
            <button type="button" onclick="alert('Pengaturan platform berhasil disimpan.')" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs rounded-xl shadow transition">
                Simpan Konfigurasi Platform
            </button>
        </div>

    </div>

</div>
@endsection
