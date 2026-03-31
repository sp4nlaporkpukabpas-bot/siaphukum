@extends('layouts.app')
@section('title', 'Riwayat Akses Dokumen | Siap-HUKUM')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-maroon-900 text-gold-400 rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-clock-rotate-left"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 uppercase tracking-tight">Riwayat Akses Dokumen</h1>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Rekam jejak lengkap preview, unduhan, dan identitas perangkat</p>
            </div>
        </div>
        <div class="text-xs font-bold text-slate-400">
            Total: <span class="text-maroon-800">{{ $logs->total() }}</span> entri
        </div>
    </div>

    {{-- Filter Panel --}}
    <form method="GET" action="{{ route('access-logs.index') }}"
          class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

            {{-- Aksi --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Aksi</label>
                <select name="action" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <option value="">Semua</option>
                    <option value="preview"                {{ request('action') == 'preview'                ? 'selected' : '' }}>Preview</option>
                    <option value="download"               {{ request('action') == 'download'               ? 'selected' : '' }}>Download</option>
                    <option value="batch_download"         {{ request('action') == 'batch_download'         ? 'selected' : '' }}>Batch Download</option>
                    <option value="download_denied"        {{ request('action') == 'download_denied'        ? 'selected' : '' }}>Download Ditolak</option>
                    <option value="batch_download_denied"  {{ request('action') == 'batch_download_denied'  ? 'selected' : '' }}>Batch Ditolak</option>
                </select>
            </div>

            {{-- User --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Pengguna</label>
                <select name="user_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <option value="">Semua</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dokumen --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Dokumen</label>
                <select name="document_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <option value="">Semua</option>
                    @foreach($documents as $d)
                        <option value="{{ $d->id }}" {{ request('document_id') == $d->id ? 'selected' : '' }}>
                            {{ Str::limit($d->name, 35) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Device Type --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Perangkat</label>
                <select name="device_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <option value="">Semua</option>
                    <option value="desktop" {{ request('device_type') == 'desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="mobile"  {{ request('device_type') == 'mobile'  ? 'selected' : '' }}>Mobile</option>
                    <option value="tablet"  {{ request('device_type') == 'tablet'  ? 'selected' : '' }}>Tablet</option>
                    <option value="bot"     {{ request('device_type') == 'bot'     ? 'selected' : '' }}>Bot</option>
                </select>
            </div>

            {{-- Dari --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
            </div>

            {{-- Sampai --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit"
                    class="px-5 py-2 bg-maroon-900 text-gold-400 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all">
                <i class="fas fa-filter mr-1"></i> Terapkan Filter
            </button>
            <a href="{{ route('access-logs.index') }}"
               class="px-5 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                Reset
            </a>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">#</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Pengguna</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Dokumen</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Perangkat</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">Browser / OS</th>
                        <th class="px-4 py-3 text-left font-black text-slate-400 uppercase tracking-widest">IP &amp; Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    {{-- Baris utama — warna merah muda jika akses ditolak --}}
                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer
                               {{ str_ends_with($log->action, '_denied') ? 'bg-rose-50/40' : '' }}"
                        onclick="toggleDetail({{ $log->id }})">

                        {{-- ID --}}
                        <td class="px-4 py-3 text-slate-300 font-mono">{{ $log->id }}</td>

                        {{-- Waktu --}}
                        <td class="px-4 py-3 text-slate-500 font-medium whitespace-nowrap">
                            {{ $log->created_at->translatedFormat('d M Y') }}<br>
                            <span class="text-[10px] text-slate-300">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>

                        {{-- Pengguna --}}
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-700">{{ $log->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $log->user->nip ?? '' }}</p>
                        </td>

                        {{-- Dokumen --}}
                        <td class="px-4 py-3 max-w-[200px]">
                            <p class="font-semibold text-slate-700 truncate">{{ $log->document->name ?? '[dihapus]' }}</p>
                            <p class="text-[10px] text-slate-400 font-mono">{{ $log->document->document_number ?? '' }}</p>
                        </td>

                        {{-- Aksi badge --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $badges = [
                                    'preview' => [
                                        'bg'   => 'bg-blue-50 text-blue-700',
                                        'icon' => 'fa-eye',
                                        'text' => 'Preview',
                                    ],
                                    'download' => [
                                        'bg'   => 'bg-emerald-50 text-emerald-700',
                                        'icon' => 'fa-download',
                                        'text' => 'Download',
                                    ],
                                    'batch_download' => [
                                        'bg'   => 'bg-amber-50 text-amber-700',
                                        'icon' => 'fa-file-zipper',
                                        'text' => 'Batch ZIP',
                                    ],
                                    'download_denied' => [
                                        'bg'   => 'bg-rose-50 text-rose-700',
                                        'icon' => 'fa-ban',
                                        'text' => 'Ditolak',
                                    ],
                                    'batch_download_denied' => [
                                        'bg'   => 'bg-rose-50 text-rose-700',
                                        'icon' => 'fa-ban',
                                        'text' => 'Batch Ditolak',
                                    ],
                                ];
                                $b = $badges[$log->action] ?? ['bg' => 'bg-slate-100 text-slate-500', 'icon' => 'fa-question', 'text' => $log->action];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 {{ $b['bg'] }} rounded-lg font-black text-[10px] uppercase">
                                <i class="fas {{ $b['icon'] }} text-[9px]"></i> {{ $b['text'] }}
                            </span>
                        </td>

                        {{-- Perangkat --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $deviceIcons  = ['desktop' => 'fa-desktop', 'mobile' => 'fa-mobile-screen', 'tablet' => 'fa-tablet-screen-button', 'bot' => 'fa-robot'];
                                $deviceColors = ['desktop' => 'text-slate-500', 'mobile' => 'text-indigo-500', 'tablet' => 'text-violet-500', 'bot' => 'text-rose-400'];
                                $dIcon  = $deviceIcons[$log->device_type]  ?? 'fa-question';
                                $dColor = $deviceColors[$log->device_type] ?? 'text-slate-400';
                            @endphp
                            <div class="flex items-center gap-1.5">
                                <i class="fas {{ $dIcon }} {{ $dColor }} text-sm"></i>
                                <div>
                                    <p class="font-semibold text-slate-600 capitalize">{{ $log->device_type ?? '-' }}</p>
                                    @if($log->device_brand || $log->device_model)
                                        <p class="text-[10px] text-slate-400">
                                            {{ implode(' ', array_filter([$log->device_brand, $log->device_model])) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Browser / OS --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($log->browser_name)
                                <p class="font-semibold text-slate-700">
                                    {{ $log->browser_name }}
                                    @if($log->browser_version)
                                        <span class="text-slate-400 font-mono text-[10px]">{{ $log->browser_version }}</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-slate-300">-</p>
                            @endif
                            @if($log->os_name)
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    <i class="fas fa-layer-group text-[9px] mr-0.5"></i>
                                    {{ $log->os_name }} {{ $log->os_version }}
                                </p>
                            @endif
                        </td>

                        {{-- IP & Lokasi --}}
                        <td class="px-4 py-3">
                            <p class="font-mono text-[10px] text-slate-500">{{ $log->ip_address ?? '-' }}</p>
                            @if($log->city_name || $log->region_name)
                                <p class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i class="fas fa-location-dot text-rose-400 text-[9px]"></i>
                                    {{ implode(', ', array_filter([$log->city_name, $log->region_name])) }}
                                </p>
                            @endif
                            @if($log->country_name)
                                <p class="text-[10px] text-slate-300 font-semibold">
                                    @if($log->country_code)
                                        <span class="inline-block bg-slate-100 px-1 rounded font-mono">{{ $log->country_code }}</span>
                                    @endif
                                    {{ $log->country_name }}
                                </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Baris detail — toggle saat baris diklik --}}
                    <tr id="detail-{{ $log->id }}" class="hidden bg-slate-50/70 border-b border-dashed border-slate-100">
                        <td colspan="8" class="px-6 py-2 space-y-1">
                            <p class="text-[10px] text-slate-400 font-mono break-all leading-relaxed">
                                <span class="font-black text-slate-500 mr-1">UA:</span>{{ $log->user_agent ?? '-' }}
                            </p>
                            @if($log->latitude && $log->longitude)
                                <p class="text-[10px] text-slate-400">
                                    <span class="font-black text-slate-500 mr-1">Koordinat:</span>
                                    <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}"
                                       target="_blank"
                                       class="text-maroon-700 hover:underline font-mono">
                                        {{ $log->latitude }}, {{ $log->longitude }}
                                        <i class="fas fa-external-link-alt text-[9px] ml-0.5"></i>
                                    </a>
                                </p>
                            @endif
                            @if(str_ends_with($log->action, '_denied'))
                                <p class="text-[10px] text-rose-500 font-bold">
                                    <i class="fas fa-triangle-exclamation mr-1"></i>
                                    Akses ditolak — user tidak memiliki izin download pada kategori ini.
                                </p>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-slate-300 font-semibold">
                            <i class="fas fa-inbox text-2xl mb-2 block"></i>
                            Tidak ada log yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <p class="mt-3 text-[10px] text-slate-400 text-center">
        <i class="fas fa-circle-info mr-1"></i>
        Klik baris untuk melihat detail User-Agent, koordinat, dan keterangan akses ditolak.
        <span class="ml-2 inline-flex items-center gap-1">
            <span class="w-2 h-2 bg-rose-100 border border-rose-200 rounded-sm inline-block"></span>
            Baris merah = akses ditolak.
        </span>
    </p>
</div>

<script>
    function toggleDetail(id) {
        const row = document.getElementById('detail-' + id);
        if (row) row.classList.toggle('hidden');
    }
</script>
@endsection
