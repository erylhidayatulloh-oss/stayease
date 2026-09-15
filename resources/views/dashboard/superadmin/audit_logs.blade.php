@extends('layouts.dashboard')

@section('title', 'Log Audit & Keamanan Sistem')

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-slate-900">Audit Trail & Keamanan Sistem</h1>
        <p class="text-xs text-slate-500">Pencatatan real-time seluruh aktivitas otorisasi, pergantian role, persetujuan payout, dan login sensitif.</p>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Waktu (WIB)</th>
                        <th class="p-4">Aktor / Pengguna</th>
                        <th class="p-4">Tindakan Keamanan</th>
                        <th class="p-4">Entitas Target</th>
                        <th class="p-4">Alamat IP & User Agent</th>
                        <th class="p-4">Data Perubahan (Payload)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($logs as $l)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                {{ $l->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $l->user->name ?? 'System' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $l->user->email ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-900 font-mono font-bold text-[10px] rounded-lg">
                                    {{ $l->action }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-700">
                                <strong>{{ $l->entity_type }}</strong> (ID: {{ $l->entity_id ?? '-' }})
                            </td>
                            <td class="p-4 text-slate-500 text-[11px] font-mono max-w-xs truncate">
                                <span class="text-slate-800 font-bold block">{{ $l->ip_address }}</span>
                                <span class="text-[10px] text-slate-400 truncate block">{{ $l->user_agent }}</span>
                            </td>
                            <td class="p-4 font-mono text-[10px] text-slate-600 max-w-xs">
                                <pre class="bg-slate-50 p-2 rounded-lg border border-slate-200 overflow-x-auto">{{ json_encode($l->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada riwayat audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
