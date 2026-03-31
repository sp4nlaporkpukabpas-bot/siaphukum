@extends('layouts.app')
@section('title', 'Master Dokumen')

@section('content')
<div class="max-w-full">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter">
                Database <span class="text-maroon-800 italic">Dokumen</span>
            </h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Manajemen seluruh arsip produk hukum KPU Pasuruan.</p>
        </div>

        <a href="{{ route('documents.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-maroon-800 hover:bg-maroon-900 text-white px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shrink-0">
            <i class="fas fa-upload"></i> Unggah Dokumen
        </a>
    </div>

    {{-- Toolbar: Search + Filter + Batch --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-5">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                <i class="fas fa-search text-slate-400 text-sm"></i>
            </span>
            <input type="text" id="searchInput"
                   class="block w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon-800 focus:border-maroon-800 text-sm font-medium transition-all outline-none"
                   placeholder="Cari nama, kategori, atau nomor dokumen...">
            <button id="clearSearch" class="hidden absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times-circle text-sm"></i>
            </button>
        </div>

        {{-- Filter Kategori --}}
        <div class="relative">
            <select id="filterKategori"
                    class="appearance-none pl-4 pr-8 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:ring-2 focus:ring-maroon-800 outline-none cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($documents->pluck('category.name')->unique()->filter()->sort() as $catName)
                    <option value="{{ $catName }}">{{ $catName }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>

        {{-- Batch Download ZIP --}}
        <button id="batchDownloadBtn"
                class="hidden items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm shrink-0">
            <i class="fas fa-file-zipper"></i>
            Unduh ZIP (<span id="selectedCount">0</span>)
        </button>
    </div>

    {{-- Info bar: hasil + select all --}}
    <div class="flex items-center justify-between mb-3 px-1">
        <p id="resultInfo" class="text-xs text-slate-400 font-medium"></p>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="selectAll" class="w-3.5 h-3.5 rounded accent-maroon-800 cursor-pointer">
            <label for="selectAll" class="text-xs font-bold text-slate-500 cursor-pointer select-none">Pilih Semua</label>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="documentTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-5 py-4 w-8"><span class="sr-only">Pilih</span></th>
                        <th class="px-5 py-4">Info Dokumen</th>
                        <th class="px-5 py-4 hidden md:table-cell">Kategori</th>
                        <th class="px-5 py-4 hidden lg:table-cell">No. Dokumen</th>
                        <th class="px-5 py-4 hidden sm:table-cell">Tanggal</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="tableBody">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/70 transition-all group document-row"
                        data-name="{{ strtolower($doc->name) }}"
                        data-category="{{ strtolower($doc->category->name) }}"
                        data-number="{{ strtolower($doc->document_number) }}">

                        {{-- Checkbox --}}
                        <td class="px-5 py-4">
                            <input type="checkbox"
                                   class="doc-checkbox w-3.5 h-3.5 rounded accent-maroon-800 cursor-pointer"
                                   value="{{ $doc->id }}">
                        </td>

                        {{-- Info Dokumen --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-red-500 group-hover:text-white transition-all">
                                    <i class="fas fa-file-pdf text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-slate-700 leading-tight truncate max-w-[180px] sm:max-w-xs lg:max-w-sm">{{ $doc->name }}</p>
                                    @if($doc->parent_id)
                                        <span class="text-[9px] font-black text-maroon-600 uppercase italic">Lampiran: {{ Str::limit($doc->parent->name, 30) }}</span>
                                    @endif
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5 sm:hidden">{{ $doc->document_number }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5 sm:hidden">{{ $doc->document_date->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-5 py-4 hidden md:table-cell">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase doc-category">
                                {{ $doc->category->name }}
                            </span>
                        </td>

                        {{-- Nomor --}}
                        <td class="px-5 py-4 hidden lg:table-cell font-mono text-xs text-slate-500 doc-number whitespace-nowrap">
                            {{ $doc->document_number }}
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-5 py-4 hidden sm:table-cell text-xs font-semibold text-slate-600 whitespace-nowrap">
                            {{ $doc->document_date->translatedFormat('d F Y') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-1.5">
                                @if(str_ends_with($doc->file_path, '.pdf'))
                                <a href="{{ route('documents.preview', $doc->id) }}" title="Pratinjau"
                                   class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @endif
                                <a href="{{ route('documents.download', $doc->id) }}" title="Unduh"
                                   class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                                <a href="{{ route('documents.edit', $doc->id) }}" title="Edit"
                                   class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus dokumen ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <i class="fas fa-folder-open text-slate-200 text-4xl mb-3 block"></i>
                            <p class="text-slate-400 font-medium text-sm">Belum ada dokumen.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResult" class="hidden p-12 text-center">
                <i class="fas fa-search text-slate-200 text-4xl mb-3 block"></i>
                <p class="text-slate-500 font-medium text-sm">Dokumen tidak ditemukan.</p>
            </div>
        </div>

        <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50 flex items-center justify-between">
            <p class="text-xs text-slate-400 font-medium">
                Total: <span id="totalVisible" class="font-black text-slate-600">{{ $documents->count() }}</span> dokumen
            </p>
            <p class="text-[10px] text-slate-400 hidden md:block">Centang dokumen lalu klik "Unduh ZIP" untuk batch download</p>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk batch ZIP (submit POST ke server) --}}
<form id="batchDownloadForm" action="{{ route('documents.batch-download') }}" method="POST" class="hidden">
    @csrf
    <div id="batchIdsContainer"></div>
</form>

{{-- Toast --}}
<div id="batchToast"
     class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-2xl items-center gap-3 text-sm font-medium">
    <i class="fas fa-spinner fa-spin text-yellow-400"></i>
    <span id="batchToastText">Menyiapkan arsip ZIP...</span>
</div>

{{-- Inject download URL ke JS via route helper agar selalu sinkron dengan web.php --}}
<script>
    const singleDownloadUrl = "{{ route('documents.download', ['document' => 0]) }}";
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput     = document.getElementById('searchInput');
    const clearBtn        = document.getElementById('clearSearch');
    const filterKat       = document.getElementById('filterKategori');
    const noResult        = document.getElementById('noResult');
    const totalVisible    = document.getElementById('totalVisible');
    const resultInfo      = document.getElementById('resultInfo');
    const selectAll       = document.getElementById('selectAll');
    const batchBtn        = document.getElementById('batchDownloadBtn');
    const selectedCountEl = document.getElementById('selectedCount');
    const batchForm       = document.getElementById('batchDownloadForm');
    const batchContainer  = document.getElementById('batchIdsContainer');
    const batchToast      = document.getElementById('batchToast');
    const batchToastText  = document.getElementById('batchToastText');
    const rows            = Array.from(document.querySelectorAll('.document-row'));

    // ── SEARCH & FILTER ──────────────────────────────────────────────────────
    function filterRows() {
        const query     = searchInput.value.toLowerCase().trim();
        const katFilter = filterKat.value.toLowerCase();
        let found = 0;

        rows.forEach(row => {
            const matchSearch = !query ||
                (row.dataset.name     || '').includes(query) ||
                (row.dataset.category || '').includes(query) ||
                (row.dataset.number   || '').includes(query);
            const matchKat = !katFilter || (row.dataset.category || '') === katFilter;

            const visible = matchSearch && matchKat;
            row.style.display = visible ? '' : 'none';
            if (visible) found++;
        });

        totalVisible.textContent = found;
        noResult.classList.toggle('hidden', found > 0 || rows.length === 0);
        clearBtn.classList.toggle('hidden', !searchInput.value);
        resultInfo.textContent = (query || katFilter)
            ? `Menampilkan ${found} dari ${rows.length} dokumen`
            : '';

        updateSelectAllState();
    }

    searchInput.addEventListener('input', filterRows);
    filterKat.addEventListener('change', filterRows);
    clearBtn.addEventListener('click', () => { searchInput.value = ''; filterRows(); searchInput.focus(); });

    // ── CHECKBOX ─────────────────────────────────────────────────────────────
    function getVisibleCheckboxes() {
        return rows
            .filter(r => r.style.display !== 'none')
            .map(r => r.querySelector('.doc-checkbox'))
            .filter(Boolean);
    }

    function getCheckedBoxes() {
        return getVisibleCheckboxes().filter(cb => cb.checked);
    }

    function updateBatchButton() {
        const n = getCheckedBoxes().length;
        selectedCountEl.textContent = n;
        batchBtn.classList.toggle('hidden', n === 0);
        batchBtn.classList.toggle('flex', n > 0);
    }

    function updateSelectAllState() {
        const visible = getVisibleCheckboxes();
        const checked = visible.filter(cb => cb.checked);
        selectAll.indeterminate = checked.length > 0 && checked.length < visible.length;
        selectAll.checked       = visible.length > 0 && checked.length === visible.length;
        updateBatchButton();
    }

    document.getElementById('tableBody').addEventListener('change', e => {
        if (e.target.classList.contains('doc-checkbox')) updateSelectAllState();
    });

    selectAll.addEventListener('change', () => {
        getVisibleCheckboxes().forEach(cb => { cb.checked = selectAll.checked; });
        updateBatchButton();
    });

    // ── BATCH DOWNLOAD → server ZIP ──────────────────────────────────────────
    batchBtn.addEventListener('click', function () {
        const checked = getCheckedBoxes();
        if (!checked.length) return;

        // 1 dokumen → gunakan route helper yang sudah di-inject, bukan URL hardcoded
        if (checked.length === 1) {
            window.location.href = singleDownloadUrl.replace('/0/', `/${checked[0].value}/`);
            return;
        }

        // Multiple → kirim ID ke server, server balas .zip
        batchContainer.innerHTML = '';
        checked.forEach(cb => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'ids[]';
            inp.value = cb.value;
            batchContainer.appendChild(inp);
        });

        showToast(`Menyiapkan ZIP untuk ${checked.length} dokumen...`);
        batchForm.submit();
        setTimeout(hideToast, 5000);
    });

    function showToast(msg) {
        batchToastText.textContent = msg;
        batchToast.classList.remove('hidden');
        batchToast.classList.add('flex');
    }
    function hideToast() {
        batchToast.classList.add('hidden');
        batchToast.classList.remove('flex');
    }

    filterRows(); // init
});
</script>
@endsection
