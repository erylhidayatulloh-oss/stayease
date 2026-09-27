@extends('layouts.dashboard')

@section('title', 'Profil & Verifikasi KTP')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-black text-slate-900">Data Profil & Verifikasi KTP</h1>
        <p class="text-xs text-slate-500">Lengkapi identitas & rekening bank Anda untuk kelancaran pencairan omzet sewa.</p>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 font-semibold">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">

        <!-- Verification Badge -->
        @if($mitra->ktp_verified_at)
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-3 text-xs text-emerald-900">
                <span class="text-2xl">🛡️</span>
                <div>
                    <strong>Status KYC: Terverifikasi (KTP Valid)</strong>
                    <p class="text-[11px] text-emerald-700">Identitas Anda telah diverifikasi oleh Admin Stayease pada {{ $mitra->ktp_verified_at->format('d M Y') }}.</p>
                </div>
            </div>
        @elseif($mitra->ktp_rejection_reason)
            <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 flex items-center gap-3 text-xs text-rose-900">
                <span class="text-2xl">❌</span>
                <div>
                    <strong>Verifikasi Ditolak — Perlu Diperbaiki</strong>
                    <p class="text-[11px] text-rose-700">Alasan: {{ $mitra->ktp_rejection_reason }}. Silakan perbaiki data/foto KTP di bawah lalu kirim ulang.</p>
                </div>
            </div>
        @elseif($mitra->ktp_photo)
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-center gap-3 text-xs text-amber-900">
                <span class="text-2xl">⏳</span>
                <div>
                    <strong>Status KYC: Menunggu Verifikasi Admin</strong>
                    <p class="text-[11px] text-amber-700">Foto KTP Anda sudah diterima dan sedang ditinjau oleh tim Admin Stayease.</p>
                </div>
            </div>
        @else
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3 text-xs text-slate-700">
                <span class="text-2xl">📋</span>
                <div>
                    <strong>Status KYC: Belum Verifikasi</strong>
                    <p class="text-[11px] text-slate-500">Unggah foto KTP di bawah ini untuk memverifikasi identitas Anda sebagai Mitra.</p>
                </div>
            </div>
        @endif

        <form action="{{ route('mitra.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
                <img src="{{ $mitra->avatar_url }}" alt="Foto profil" class="w-16 h-16 rounded-2xl object-cover border border-slate-200">
                <div class="flex-1 space-y-1">
                    <label class="block font-bold text-slate-700">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/png, image/jpeg" class="w-full text-[11px] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-bold hover:file:bg-emerald-100">
                    <p class="text-[10px] text-slate-400">JPG/PNG, maks. 2MB.</p>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Lengkap / Penanggung Jawab *</label>
                <input type="text" name="name" value="{{ old('name', $mitra->name) }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email', $mitra->email) }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nomor WhatsApp Aktif *</label>
                <input type="tel" name="phone" value="{{ old('phone', $mitra->phone) }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nomor Induk Kependudukan (NIK KTP)</label>
                <input type="text" name="ktp_number" value="{{ old('ktp_number', $mitra->ktp_number) }}" maxlength="16" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Lengkap Persis Seperti di KTP</label>
                <input type="text" name="ktp_name" value="{{ old('ktp_name', $mitra->ktp_name) }}" placeholder="Isi kalau berbeda dari nama akun di atas" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                <p class="text-[10px] text-slate-400">Kalau nama akun Anda salah ketik/beda dengan nama panggilan, isi nama yang PERSIS sama dengan KTP di sini — bukan nama akun di atas.</p>
                @if($mitra->hasKtpNameMismatch())
                    <p class="text-[11px] font-bold text-amber-600">⚠️ Nama akun ("{{ $mitra->name }}") berbeda dengan nama KTP ("{{ $mitra->ktp_name }}"). Ini normal kalau memang beda nama panggilan — Admin akan tetap memverifikasi berdasarkan foto KTP.</p>
                @endif
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Foto KTP (JPG/PNG, maks. 4MB)</label>
                @if($mitra->ktp_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $mitra->ktp_photo) }}" alt="Foto KTP" class="h-32 rounded-xl border border-slate-200 object-cover">
                        <p class="text-[10px] text-slate-400 mt-1">Foto saat ini. Unggah file baru untuk menggantinya.</p>
                    </div>
                @endif
                <input type="file" name="ktp_photo" accept="image/png, image/jpeg" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-900">Rekening Bank untuk Pencairan Omzet</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $mitra->bank_name) }}" placeholder="Cth: BCA" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $mitra->bank_account_number) }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-700">Nama Pemilik Rekening</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $mitra->bank_account_holder) }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kata Sandi Baru</label>
                    <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Ulangi Sandi Baru</label>
                    <input type="password" name="password_confirmation" minlength="6" placeholder="Ulangi sandi baru" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow transition">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
