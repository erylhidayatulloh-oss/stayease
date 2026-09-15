@extends('layouts.dashboard')

@section('title', 'Tambah Listing Properti Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Form Pendaftaran Properti Baru</h1>
            <p class="text-xs text-slate-500">Isi data akurat untuk mempercepat proses verifikasi oleh tim Admin Stayease.</p>
        </div>
        <a href="{{ route('mitra.properties') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Kembali</a>
    </div>

    <form action="{{ route('mitra.properties.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6 text-xs">
        @csrf

        <div class="space-y-4">
            <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">1. Informasi Utama & Lokasi</h3>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Nama Listing / Properti *</label>
                <input type="text" name="title" placeholder="Cth: Kost Griya Asri Sleman UGM" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Tipe Hunian *</label>
                    <select name="property_type" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                        <option value="kost_campur">Kost Campur</option>
                        <option value="kost_putri">Kost Khusus Putri</option>
                        <option value="kost_putra">Kost Khusus Putra</option>
                        <option value="apartemen">Apartemen</option>
                        <option value="villa">Villa & Co-Living</option>
                        <option value="kontrakan">Rumah Kontrakan</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kota / Wilayah *</label>
                    <select name="city_id" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                        @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kecamatan / Area *</label>
                    <input type="text" name="sub_district" placeholder="Cth: Tebet / Pogung / Canggu" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Batasan Gender</label>
                    <input type="text" name="gender_restriction" value="Campur (Pria/Wanita)" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Alamat Lengkap *</label>
                <textarea name="address" rows="2" placeholder="Jl. Contoh No. 123..." required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-100">2. Harga & Detail Kamar</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Harga Sewa Bulanan (Rp) *</label>
                    <input type="number" name="base_price_monthly" value="2000000" step="50000" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Uang Jaminan / Deposit (Rp) *</label>
                    <input type="number" name="deposit_amount" value="500000" step="50000" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:outline-none focus:border-emerald-500">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Kebijakan Listrik</label>
                    <select name="electricity_policy" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                        <option value="token_mandiri">Token Mandiri</option>
                        <option value="include">Include Listrik</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Nama Tipe Kamar *</label>
                    <input type="text" name="unit_name" value="Deluxe Room" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Ukuran Kamar (m²) *</label>
                    <input type="number" name="unit_size" value="16" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>

                <div class="space-y-1">
                    <label class="block font-bold text-slate-700">Jumlah Unit Tersedia *</label>
                    <input type="number" name="unit_total" value="5" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block font-bold text-slate-700">Deskripsi Lengkap Properti *</label>
                <textarea name="description" rows="3" placeholder="Jelaskan keunggulan hunian, akses terdekat, dan aturan..." required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow transition">
                Daftarkan Properti ke Antrean Verifikasi Admin
            </button>
        </div>
    </form>

</div>
@endsection
