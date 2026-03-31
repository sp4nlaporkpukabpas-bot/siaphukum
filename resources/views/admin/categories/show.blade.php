@extends('layouts.app')
@section('title', $category->name . ' | Siap-HUKUM')

@section('content')
<div class="max-w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">
        <a href="{{ route('dashboard') }}" class="hover:text-maroon-800 transition-colors">Dashboard</a>
        <i class="fas fa-chevron-right text-[8px]"></i>
        <span class="text-maroon-800 truncate">{{ $category->name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-7">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-maroon-900 text-yellow-400 rounded-xl shadow-lg flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight uppercase leading-none">
                    {{ $category->name }}
                </h1>
                <p class="text-slate-500 font-medium text-xs mt-1 flex items-center gap-1.5">
                    <i class="fas fa-file-invoice text-maroon-800 text-[10px]"></i>
                    <span id="docCount" class="font-bold text-slate-700">{{ $documents->count() }}</span> dokumen tersedia
                </p>
            </div>
        </div>

        {{-- Badge izin akses --}}
        <div class="flex flex-wrap gap-2 shrink-0">
            @if($permission->pivot->can_view)
                <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                    <i class="fas fa-eye"></i> View
                </span>
            @endif
            @if($permission->pivot->can_download)
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                    <i class="fas fa-download"></i> Download
                </span>
            @endif
        </div>
    </div>

    {{-- Toolbar: Search + Batch --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-5">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                <i class="fas fa-search text-slate-400 text-sm"></i>
            </span>
            <input type="text" id="searchInput"
                   class="block w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon-800 focus:border-maroon-800 text-sm font-medium outline-none transition-all"
                   placeholder="Cari nama, nomor, atau tanggal...">
            <button id="clearSearch"
                    class="hidden absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times-circle text-sm"></i>
            </button>
        </div>

        {{-- Batch Download ZIP --}}
        @if($permission->pivot->can_download)
        <button id="batchDownloadBtn"
                class="hidden items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm shrink-0">
            <i class="fas fa-file-zipper"></i>
            Unduh ZIP (<span id="selectedCount">0</span>)
        </button>
        @endif
    </div>

    {{-- Info bar: hasil + select all --}}
    <div class="flex items-center justify-between mb-3 px-1">
        <p id="resultInfo" class="text-xs text-slate-400 font-medium"></p>
        @if($permission->pivot->can_download)
        <div class="flex items-center gap-2">
            <input type="checkbox" id="selectAll" class="w-3.5 h-3.5 rounded accent-maroon-800 cursor-pointer">
            <label for="selectAll" class="text-xs font-bold text-slate-500 cursor-pointer select-none">Pilih Semua</label>
        </div>
        @endif
    </div>

    {{-- Document List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Desktop Header --}}
        <div class="hidden md:grid bg-slate-50 border-b border-slate-100 px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest
            {{ $permission->pivot->can_download ? 'grid-cols-12' : 'grid-cols-11' }}">
            @if($permission->pivot->can_download)
            <div class="col-span-1 text-center"><span class="sr-only">Pilih</span></div>
            @endif
            <div class="{{ $permission->pivot->can_download ? 'col-span-6' : 'col-span-7' }}">Nama Produk Hukum</div>
            <div class="col-span-2 text-center">Nomor</div>
            <div class="col-span-2 text-center">Tgl. Penetapan</div>
            <div class="col-span-1 text-right">Aksi</div>
        </div>

        <div class="divide-y divide-slate-50" id="docList">
            @forelse($documents as $doc)
            {{--
                SATU checkbox per dokumen, disimpan di data-attribute row.
                Mobile & desktop layout berbagi nilai dari checkbox yang SAMA
                via JavaScript — tidak ada duplikasi elemen input.
            --}}
            <div class="document-row hover:bg-slate-50/70 transition-all group"
                 data-id="{{ $doc->id }}"
                 data-search="{{ strtolower($doc->name . ' ' . $doc->document_number . ' ' . $doc->document_date->translatedFormat('d F Y')) }}">

                {{-- ── MOBILE LAYOUT ─────────────────────────────────── --}}
                <div class="flex md:hidden items-start gap-3 p-4">

                    @if($permission->pivot->can_download)
                    {{-- Checkbox representatif — satu-satunya checkbox nyata per row --}}
                    <input type="checkbox"
                           class="doc-checkbox mt-1 w-3.5 h-3.5 rounded accent-maroon-800 cursor-pointer shrink-0"
                           value="{{ $doc->id }}">
                    @endif

                    <div class="w-9 h-9 bg-red-50 text-red-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-red-500 group-hover:text-white transition-all mt-0.5">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-slate-800 leading-snug">{{ $doc->name }}</p>
                        @if($doc->parent_id)
                            <p class="text-[9px] text-maroon-600 font-black uppercase mt-0.5 flex items-center gap-1">
                                <i class="fas fa-link text-[8px]"></i>
                                Lampiran: <span class="normal-case font-bold">{{ Str::limit($doc->parent->name, 30) }}</span>
                            </p>
                        @endif
                        <div class="flex flex-wrap gap-x-3 gap-y-0.5 mt-1.5">
                            <span class="font-mono text-[10px] text-slate-400 font-bold">{{ $doc->document_number }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $doc->document_date->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="flex gap-1.5 mt-3">
                            @if($permission->pivot->can_view)
                            <a href="{{ route('documents.preview', $doc->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-maroon-900 hover:text-white transition-all">
                                <i class="fas fa-eye text-[10px]"></i> Pratinjau
                            </a>
                            @endif
                            @if($permission->pivot->can_download)
                            <a href="{{ route('documents.download', $doc->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold hover:bg-emerald-600 hover:text-white transition-all">
                                <i class="fas fa-download text-[10px]"></i> Unduh
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── DESKTOP LAYOUT ────────────────────────────────── --}}
                {{-- Checkbox di desktop adalah VISUAL PROXY — dikendalikan JS, bukan input nyata --}}
                <div class="hidden md:grid items-center px-6 py-4 gap-2
                    {{ $permission->pivot->can_download ? 'grid-cols-12' : 'grid-cols-11' }}">

                    @if($permission->pivot->can_download)
                    <div class="col-span-1 flex justify-center">
                        {{-- Proxy visual: klik ini toggle checkbox asli (mobile) via JS --}}
                        <span class="desktop-checkbox-proxy w-3.5 h-3.5 rounded border-2 border-slate-300 flex items-center justify-center cursor-pointer transition-all"
                              data-for="{{ $doc->id }}">
                        </span>
                    </div>
                    @endif

                    {{-- Nama --}}
                    <div class="{{ $permission->pivot->can_download ? 'col-span-6' : 'col-span-7' }} flex items-center gap-3">
                        <div class="w-9 h-9 bg-red-50 text-red-400 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-red-500 group-hover:text-white transition-all">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-800 leading-snug truncate group-hover:text-maroon-900 transition-colors">
                                {{ $doc->name }}
                            </p>
                            @if($doc->parent_id)
                                <p class="text-[9px] text-maroon-600 font-black uppercase mt-0.5 flex items-center gap-1">
                                    <i class="fas fa-link text-[8px]"></i>
                                    Lampiran: <span class="normal-case font-bold text-slate-500">{{ Str::limit($doc->parent->name, 35) }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Nomor --}}
                    <div class="col-span-2 text-center">
                        <span class="font-mono text-[11px] font-bold text-slate-500">{{ $doc->document_number }}</span>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-span-2 text-center">
                        <span class="text-xs font-semibold text-slate-600">
                            {{ $doc->document_date->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    {{-- Aksi --}}
                    <div class="col-span-1 flex justify-end gap-1.5">
                        @if($permission->pivot->can_view)
                        <a href="{{ route('documents.preview', $doc->id) }}" title="Pratinjau"
                           class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center hover:bg-maroon-900 hover:text-white transition-all">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        @endif
                        @if($permission->pivot->can_download)
                        <a href="{{ route('documents.download', $doc->id) }}" title="Unduh"
                           class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                            <i class="fas fa-download text-xs"></i>
                        </a>
                        @endif
                    </div>
                </div>

            </div>
            @empty
            <div class="px-8 py-16 text-center">
                <i class="fas fa-folder-open text-slate-200 text-4xl mb-3 block"></i>
                <p class="text-slate-400 font-medium text-sm">Tidak ada dokumen dalam kategori ini.</p>
            </div>
            @endforelse
        </div>

        {{-- Not Found State --}}
        <div id="noResults" class="hidden px-8 py-16 text-center">
            <i class="fas fa-search text-slate-200 text-4xl mb-3 block"></i>
            <p class="text-slate-500 font-medium text-sm">Dokumen tidak ditemukan.</p>
            <button onclick="resetSearch()"
                    class="mt-3 text-maroon-800 font-black text-xs uppercase tracking-widest hover:underline">
                Hapus Pencarian
            </button>
        </div>

        {{-- Footer --}}
        <div class="border-t border-slate-100 px-6 py-3 bg-slate-50/50 flex items-center justify-between">
            <p class="text-xs text-slate-400 font-medium">
                Total: <span id="docCountFooter" class="font-black text-slate-600">{{ $documents->count() }}</span> dokumen
            </p>
            @if($permission->pivot->can_download)
            <p class="text-[10px] text-slate-400 hidden sm:block">Centang dokumen lalu klik "Unduh ZIP" untuk batch download</p>
            @endif
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk batch ZIP (POST ke server) --}}
@if($permission->pivot->can_download)
<form id="batchDownloadForm" action="{{ route('documents.batch-download') }}" method="POST" class="hidden">
    @csrf
    <div id="batchIdsContainer"></div>
</form>
@endif

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
    const noResults       = document.getElementById('noResults');
    const docCount        = document.getElementById('docCount');
    const docCountFooter  = document.getElementById('docCountFooter');
    const resultInfo      = document.getElementById('resultInfo');
    const selectAll       = document.getElementById('selectAll');
    const batchBtn        = document.getElementById('batchDownloadBtn');
    const selectedCountEl = document.getElementById('selectedCount');
    const batchForm       = document.getElementById('batchDownloadForm');
    const batchContainer  = document.getElementById('batchIdsContainer');
    const batchToast      = document.getElementById('batchToast');
    const batchToastText  = document.getElementById('batchToastText');
    const rows            = Array.from(document.querySelectorAll('.document-row'));
    const totalCount      = rows.length;

    // ── HELPER: satu checkbox nyata per row (ada di blok mobile) ─────────────
    function getCheckboxForRow(row) {
        return row.querySelector('.doc-checkbox');
    }

    function getAllCheckboxes() {
        return rows
            .filter(r => !r.classList.contains('hidden'))
            .map(r => getCheckboxForRow(r))
            .filter(Boolean);
    }

    function getCheckedBoxes() {
        return getAllCheckboxes().filter(cb => cb.checked);
    }

    // ── PROXY VISUAL DESKTOP ─────────────────────────────────────────────────
    function syncProxyVisual(checkbox) {
        const docId = checkbox.value;
        const proxy = document.querySelector(`.desktop-checkbox-proxy[data-for="${docId}"]`);
        if (!proxy) return;
        if (checkbox.checked) {
            proxy.classList.add('bg-maroon-800', 'border-maroon-800');
            proxy.innerHTML = '<svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
        } else {
            proxy.classList.remove('bg-maroon-800', 'border-maroon-800');
            proxy.innerHTML = '';
        }
    }

    function syncAllProxies() {
        rows.forEach(row => {
            const cb = getCheckboxForRow(row);
            if (cb) syncProxyVisual(cb);
        });
    }

    // Klik proxy desktop → toggle checkbox asli
    document.getElementById('docList').addEventListener('click', function (e) {
        const proxy = e.target.closest('.desktop-checkbox-proxy');
        if (!proxy) return;
        const docId = proxy.dataset.for;
        const cb    = document.querySelector(`.doc-checkbox[value="${docId}"]`);
        if (cb) {
            cb.checked = !cb.checked;
            syncProxyVisual(cb);
            updateSelectAllState();
        }
    });

    // ── BATCH BUTTON ─────────────────────────────────────────────────────────
    function updateBatchButton() {
        if (!batchBtn) return;
        const n = getCheckedBoxes().length;
        if (selectedCountEl) selectedCountEl.textContent = n;
        batchBtn.classList.toggle('hidden', n === 0);
        batchBtn.classList.toggle('flex',   n > 0);
    }

    function updateSelectAllState() {
        if (!selectAll) return;
        const visible = getAllCheckboxes();
        const checked = visible.filter(cb => cb.checked);
        selectAll.indeterminate = checked.length > 0 && checked.length < visible.length;
        selectAll.checked       = visible.length > 0 && checked.length === visible.length;
        updateBatchButton();
    }

    // Perubahan pada checkbox asli (mobile atau programatik)
    document.getElementById('docList').addEventListener('change', function (e) {
        if (e.target.classList.contains('doc-checkbox')) {
            syncProxyVisual(e.target);
            updateSelectAllState();
        }
    });

    // Pilih Semua
    selectAll?.addEventListener('change', function () {
        getAllCheckboxes().forEach(cb => { cb.checked = selectAll.checked; });
        syncAllProxies();
        updateBatchButton();
    });

    // ── SEARCH ───────────────────────────────────────────────────────────────
    function filterRows() {
        const query = searchInput.value.toLowerCase().trim();
        let found = 0;

        rows.forEach(row => {
            const match = !query || (row.dataset.search || '').includes(query);
            row.classList.toggle('hidden', !match);
            if (match) found++;
        });

        const displayN = query ? found : totalCount;
        if (docCount)       docCount.textContent       = displayN;
        if (docCountFooter) docCountFooter.textContent = displayN;

        noResults.classList.toggle('hidden', found > 0 || !query);
        clearBtn.classList.toggle('hidden', !searchInput.value);
        resultInfo.textContent = query ? `Menampilkan ${found} dari ${totalCount} dokumen` : '';

        updateSelectAllState();
    }

    searchInput.addEventListener('input', filterRows);
    clearBtn?.addEventListener('click', () => { searchInput.value = ''; filterRows(); searchInput.focus(); });

    window.resetSearch = function () {
        searchInput.value = '';
        filterRows();
        searchInput.focus();
    };

    // ── BATCH DOWNLOAD → server ZIP ──────────────────────────────────────────
    batchBtn?.addEventListener('click', function () {
        const checked = getCheckedBoxes();
        if (!checked.length) return;

        // 1 dokumen → gunakan route helper yang sudah di-inject, bukan URL hardcoded
        if (checked.length === 1) {
            window.location.href = singleDownloadUrl.replace('/0/', `/${checked[0].value}/`);
            return;
        }

        // Multiple → POST ke server, server kembalikan .zip
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
        if (!batchToast) return;
        batchToastText.textContent = msg;
        batchToast.classList.remove('hidden');
        batchToast.classList.add('flex');
    }
    function hideToast() {
        if (!batchToast) return;
        batchToast.classList.add('hidden');
        batchToast.classList.remove('flex');
    }
});
</script>
@endsection
