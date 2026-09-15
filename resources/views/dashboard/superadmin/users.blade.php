@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna & Otoritas Role')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Pengguna & Otoritas Role</h1>
            <p class="text-xs text-slate-500">Kelola akun seluruh pengguna, ubah hak akses (User, Mitra, Admin, Superadmin), dan status blokir.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-3xl p-4 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="text-slate-400 font-bold">Filter Role:</span>
            <a href="{{ route('superadmin.users') }}" class="px-3 py-1 rounded-xl font-bold {{ !request('role') ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700' }}">Semua</a>
            <a href="{{ route('superadmin.users', ['role' => 'user']) }}" class="px-3 py-1 rounded-xl font-bold {{ request('role') === 'user' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700' }}">User</a>
            <a href="{{ route('superadmin.users', ['role' => 'mitra']) }}" class="px-3 py-1 rounded-xl font-bold {{ request('role') === 'mitra' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700' }}">Mitra</a>
            <a href="{{ route('superadmin.users', ['role' => 'admin']) }}" class="px-3 py-1 rounded-xl font-bold {{ request('role') === 'admin' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700' }}">Admin</a>
            <a href="{{ route('superadmin.users', ['role' => 'superadmin']) }}" class="px-3 py-1 rounded-xl font-bold {{ request('role') === 'superadmin' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700' }}">Superadmin</a>
        </div>

        <form action="{{ route('superadmin.users') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / HP..." class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-purple-500">
            <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white font-bold rounded-xl">Cari</button>
        </form>
    </div>

    <!-- Users Master Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Pengguna</th>
                        <th class="p-4">Role Akses</th>
                        <th class="p-4">Status Akun</th>
                        <th class="p-4">Verifikasi KTP</th>
                        <th class="p-4">Aktivitas</th>
                        <th class="p-4 text-right">Otorisasi & Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $u->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80' }}" class="w-9 h-9 rounded-xl object-cover border border-slate-200">
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $u->email }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $u->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <form action="{{ route('superadmin.users.update_role', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="px-2.5 py-1 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 cursor-pointer">
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>👤 User (Penyewa)</option>
                                        <option value="mitra" {{ $u->role === 'mitra' ? 'selected' : '' }}>🏠 Mitra (Pemilik)</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>🛡️ Admin Operasional</option>
                                        <option value="superadmin" {{ $u->role === 'superadmin' ? 'selected' : '' }}>👑 Superadmin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4">
                                <form action="{{ route('superadmin.users.toggle_status', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg text-[11px] font-bold border {{ $u->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-rose-50 text-rose-800 border-rose-300' }}">
                                        <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>● Aktif</option>
                                        <option value="suspended" {{ $u->status === 'suspended' ? 'selected' : '' }}>⏸ Suspended</option>
                                        <option value="banned" {{ $u->status === 'banned' ? 'selected' : '' }}>🚫 Banned</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4">
                                @if($u->ktp_verified_at)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">✓ KYC Valid</span>
                                @else
                                    <form action="{{ route('superadmin.users.verify_ktp', $u->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-0.5 bg-amber-100 hover:bg-emerald-600 hover:text-white text-amber-800 text-[10px] font-bold rounded-full transition">
                                            Verifikasi Sekarang
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">
                                {{ $u->properties_count }} Properti • {{ $u->bookings_count }} Sewa
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('role.switch', $u->role) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-[10px] transition">
                                    Impersonate Akun
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada data pengguna yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
