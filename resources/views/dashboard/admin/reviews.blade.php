@extends('layouts.dashboard')

@section('title', 'Moderasi Ulasan & Testimoni')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Moderasi Ulasan & Konten Penyewa</h1>
        <p class="text-xs text-slate-500">Tinjau ulasan publik, sembunyikan spam atau komentar tidak pantas untuk menjaga standar komunitas.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Penyewa & Properti</th>
                        <th class="p-4">Rating</th>
                        <th class="p-4">Isi Ulasan & Tanggapan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50/80 transition align-top">
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $rev->user->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $rev->property->title }}</div>
                            </td>
                            <td class="p-4 text-amber-500 font-black">
                                ★ {{ $rev->rating }}.0
                            </td>
                            <td class="p-4 text-slate-700 max-w-sm space-y-2">
                                <p>"{{ $rev->comment }}"</p>

                                @if($rev->owner_reply)
                                    <div class="p-2.5 bg-emerald-50/50 rounded-xl border border-emerald-200 text-[11px] space-y-0.5">
                                        <div class="font-bold text-emerald-800">Tanggapan Pengelola/Admin:</div>
                                        <p class="text-slate-700">{{ $rev->owner_reply }}</p>
                                    </div>
                                @else
                                    <form action="{{ route('admin.reviews.reply', $rev->id) }}" method="POST" class="flex gap-1.5">
                                        @csrf
                                        <input type="text" name="reply" placeholder="Tulis tanggapan sebagai Admin..." required class="flex-1 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-[11px] focus:outline-none focus:border-emerald-500">
                                        <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-[11px] rounded-lg transition">
                                            Balas
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($rev->status === 'published')
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full">TAYANG</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-black rounded-full">DISEMBUNYIKAN</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.reviews.toggle_status', $rev->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg font-bold text-[11px] transition">
                                        {{ $rev->status === 'published' ? 'Sembunyikan' : 'Tayangkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Belum ada ulasan yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
