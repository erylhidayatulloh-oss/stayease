@extends('layouts.dashboard')

@section('title', 'Pesan Pengelola')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Pusat Pesan & Komunikasi</h1>
        <p class="text-xs text-slate-500">Percakapan langsung dengan pengelola dan pemilik properti terdaftar.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Conversation List (3 cols) -->
        <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-200 shadow-sm p-3 space-y-1.5 h-fit">
            <p class="text-[10px] font-black text-slate-400 uppercase px-2 pt-1 pb-2">Percakapan</p>
            @forelse($partners as $partner)
                <a href="{{ route('user.messages', ['with' => $partner->id]) }}"
                   class="block px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $activePartnerId === $partner->id ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $partner->name }}
                </a>
            @empty
                <p class="text-[11px] text-slate-400 px-2 py-3">Belum ada percakapan. Kunjungi sebuah listing lalu klik "Chat dengan Pemilik" untuk memulai.</p>
            @endforelse
        </div>

        <!-- Chat Stream (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col h-[520px]">
            <div class="p-4 bg-slate-900 text-white rounded-t-3xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                    <div>
                        <div class="font-bold text-xs">
                            @if($activePartnerId && $partners->firstWhere('id', $activePartnerId))
                                Chat dengan {{ $partners->firstWhere('id', $activePartnerId)->name }}
                            @else
                                Pusat Pesan
                            @endif
                        </div>
                        <div class="text-[10px] text-slate-400">Terkoneksi aman dengan enkripsi Stayease</div>
                    </div>
                </div>
            </div>

            <!-- Messages Box -->
            <div id="chat-messages-box" class="p-4 flex-1 overflow-y-auto space-y-3 bg-slate-50 text-xs">
                @forelse($messages as $m)
                    <div class="flex flex-col {{ $m->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <div class="p-3 rounded-2xl max-w-sm {{ $m->sender_id === auth()->id() ? 'bg-emerald-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none shadow-sm' }}">
                            {{ $m->content }}
                        </div>
                        <span class="text-[10px] text-slate-400 mt-0.5 px-1">{{ $m->created_at->format('H:i') }} • {{ $m->sender->name }}</span>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        @if($activePartnerId)
                            Belum ada percakapan. Kirim pesan pertama Anda di bawah.
                        @else
                            Pilih atau mulai percakapan untuk melihat pesan di sini.
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Send Form -->
            <form action="{{ route('user.messages.send') }}" method="POST" class="p-3 bg-white border-t border-slate-200 flex gap-2 rounded-b-3xl">
                @csrf
                <input type="hidden" name="recipient_id" value="{{ $activePartnerId }}">
                <input type="text" name="content" placeholder="Tulis pesan ke pemilik kost..." required {{ $activePartnerId ? '' : 'disabled' }} class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500 disabled:opacity-50">
                <button type="submit" {{ $activePartnerId ? '' : 'disabled' }} class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow transition">
                    Kirim
                </button>
            </form>
        </div>

        <!-- Help Info (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">
                🛡️
            </div>
            <h3 class="font-extrabold text-sm text-slate-900">Panduan Transaksi Aman</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Selalu lakukan pembayaran sewa dan konfirmasi deposit melalui nomor Virtual Account atau QRIS resmi di dalam platform Stayease untuk mendapatkan jaminan garansi 100%.
            </p>
            <div class="p-3 bg-emerald-50 rounded-2xl text-[11px] text-emerald-900 border border-emerald-200">
                Layanan Customer Care WhatsApp 24 Jam: <strong>+62-811-9876-5432</strong>
            </div>
        </div>

    </div>

</div>

<script>
    // Bug fix: pesan terbaru sekarang ditampilkan di paling bawah (urutan lama -> baru),
    // jadi kotak chat perlu otomatis di-scroll ke bawah supaya pesan terbaru langsung terlihat.
    document.addEventListener('DOMContentLoaded', function () {
        var box = document.getElementById('chat-messages-box');
        if (box) box.scrollTop = box.scrollHeight;
    });
</script>
@endsection
