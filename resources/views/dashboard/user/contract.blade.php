@extends('layouts.dashboard')

@section('title', 'Surat Perjanjian Sewa Digital')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('user.bookings') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1">
            &larr; Kembali ke Daftar Booking
        </a>

        <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
            <span>🖨️ Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Contract Document Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-card space-y-6 text-slate-800">
        
        <!-- Header Document -->
        <div class="text-center pb-6 border-b-2 border-slate-900 space-y-1">
            <div class="text-xs font-bold uppercase tracking-widest text-emerald-700">STAYEASE INDONESIA • OFFICIAL DIGITAL CONTRACT</div>
            <h1 class="text-xl sm:text-2xl font-black uppercase">SURAT PERJANJIAN SEWA MENYEWA HUNIAN</h1>
            <p class="text-xs text-slate-500 font-mono">Nomor Registrasi Kontrak: STY/LEG/{{ $booking->booking_code }}/2026</p>
        </div>

        <!-- Agreement Terms -->
        <div class="space-y-4 text-xs sm:text-sm leading-relaxed">
            <p>
                Pada hari ini, telah disepakati Perjanjian Sewa Menyewa Hunian yang mengikat secara hukum antara pihak-pihak di bawah ini:
            </p>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2 text-xs">
                <div>
                    <strong>PIHAK PERTAMA (Pengelola / Pemilik):</strong>
                    <div class="text-slate-600">{{ $booking->property->mitra->name }} (Pemilik Sah Properti {{ $booking->property->title }})</div>
                </div>
                <div>
                    <strong>PIHAK KEDUA (Penyewa):</strong>
                    <div class="text-slate-600">{{ $booking->tenant_name }} (NIK: {{ $booking->tenant_nik }}, Telp: {{ $booking->tenant_phone }})</div>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 1 — Objek Sewa & Masa Tinggal</h4>
                <p>
                    Pihak Pertama menyewakan kepada Pihak Kedua unit kamar <strong>{{ $booking->unit->name }}</strong> yang berlokasi di <strong>{{ $booking->property->address }}, {{ $booking->property->sub_district }}, {{ $booking->property->city->name ?? 'Indonesia' }}</strong> selama <strong>{{ $booking->duration_months }} Bulan</strong>, terhitung mulai tanggal <strong>{{ $booking->check_in_date->format('d F Y') }}</strong> sampai dengan <strong>{{ $booking->check_in_date->copy()->addMonths($booking->duration_months)->format('d F Y') }}</strong>.
                </p>

                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 2 — Biaya Sewa & Uang Jaminan (Deposit)</h4>
                <p>
                    Total biaya sewa yang telah dibayarkan lunas sebesar <strong>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</strong> via metode pembayaran resmi <strong>{{ strtoupper($booking->payment_method) }}</strong>. Uang jaminan (deposit) sebesar <strong>Rp {{ number_format($booking->deposit_total, 0, ',', '.') }}</strong> akan dikembalikan penuh kepada Pihak Kedua setelah masa sewa berakhir dan unit dikembalikan dalam keadaan baik.
                </p>

                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 3 — Tata Tertib & Hak Kewajiban</h4>
                <p>
                    Penyewa wajib mematuhi seluruh tata tertib lingkungan properti, menjaga fasilitas yang disediakan, tidak melakukan perbuatan melanggar hukum, dan menjaga ketertiban umum.
                </p>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div class="space-y-4">
                <span class="text-slate-500 block">Pihak Pertama (Pengelola),</span>
                <div class="h-16 flex items-center justify-center font-bold text-emerald-800 text-sm font-mono border border-dashed border-emerald-300 rounded-xl bg-emerald-50/50">
                    [E-VERIFIED BY STAYEASE]
                </div>
                <strong>{{ $booking->property->mitra->name }}</strong>
            </div>

            <div class="space-y-4">
                <span class="text-slate-500 block">Pihak Kedua (Penyewa),</span>
                <div class="h-16 flex items-center justify-center font-bold text-emerald-800 text-sm font-mono border border-dashed border-emerald-300 rounded-xl bg-emerald-50/50">
                    [DIGITALLY SIGNED & E-KYC]
                </div>
                <strong>{{ $booking->tenant_name }}</strong>
            </div>
        </div>

    </div>

</div>
@endsection
