@extends('layouts.app')
@section('title', 'Kategori: ' . $category->name . ' | Siap-HUKUM')

@section('content')
<div class="max-w-full">
    {{-- Breadcrumb & Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
            <a href="{{ route('dashboard') }}" class="hover:text-maroon-800 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-maroon-800">Arsip Kategori</span>
        </div>

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-maroon-900 text-yellow-400 rounded-2xl shadow-2xl flex items-center justify-center text-xl md:text-2xl border border-white/10 shrink-0">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-4xl font-extrabold text-slate-900 tracking-tighter leading-none uppercase">
                        {{ $category->name }}
                    </h1>
                    <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 text-xs md:text-sm">
                        <i class="fas fa-file-invoice text-maroon-800"></i>
                        Terdapat <span id="docCount" class="font-bold">{{ $documents->count() }}</span> dokumen
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($permission->pivot->can_view)
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-2">
                        <i class="fas fa-eye"></i> View Access
                    </span>
                @endif
                @if($permission->pivot->can_download)
                    <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-2">
                        <i class="fas fa-download"></i> Download Access
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="mb-6 relative group max-w-md">
        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
            <i class="fas fa-search text-slate-300 group-focus-within:text-maroon-800 transition-colors"></i>
        </div>
        <input type="text" id="searchInput" 
            class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-maroon-50 focus:border-maroon-900 outline-none transition-all text-sm font-bold placeholder:text-slate-300 uppercase tracking-widest"
            placeholder="Cari nama, nomor, atau tahun...">
    </div>

    {{-- Documents Container --}}
    <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        {{-- Header Tabel (Hanya muncul di Desktop) --}}
        <div class="hidden md:block bg-slate-50 border-b border-slate-100">
            <div class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-8 py-6">
                <div class="w-1/2">Nama Produk Hukum</div>
                <div class="w-1/6 text-center">Nomor</div>
                <div class="w-1/6 text-center">Tgl. Penetapan</div>
                <div class="w-1/6 text-right">Aksi</div>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($documents as $doc)
                <div class="document-row hover:bg-maroon-50/30 transition-all group p-6 md:px-8 md:py-6">
                    {{-- Grid System: 1 Kolom di Mobile, Flex Row di Desktop --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-0">
                        
                        {{-- Nama Produk Hukum --}}
                        <div class="md:w-1/2 flex items-start gap-4">
                            <div class="mt-1 w-10 h-10 shrink-0 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-maroon-900 group-hover:text-yellow-400 transition-all">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm md:text-base text-slate-800 leading-snug group-hover:text-maroon-900 transition-colors italic search-target">
                                    {{ $doc->name }}
                                </p>
                                @if($doc->parent_id)
                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <span class="text-[9px] font-black text-maroon-700 uppercase tracking-tighter shrink-0 flex items-center gap-1">
                                            <i class="fas fa-link text-[8px]"></i> Lampiran:
                                        </span>
                                        <span class="text-[10px] text-slate-500 font-bold search-target">{{ $doc->parent->name }}</span>
                                        <span class="font-mono bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 text-[9px] text-slate-400 search-target">
                                            {{ $doc->parent->document_number }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata Section (Nomor & Tanggal) --}}
                        <div class="flex flex-row md:contents gap-4">
                            {{-- Nomor --}}
                            <div class="flex-1 md:w-1/6 md:text-center">
                                <span class="block md:hidden text-[9px] font-black text-slate-400 uppercase mb-1">Nomor</span>
                                <span class="inline-block bg-slate-50 md:bg-transparent px-2 md:px-0 py-1 rounded text-[11px] font-mono font-bold text-slate-500 border border-slate-100 md:border-0 search-target">
                                    {{ $doc->document_number }}
                                </span>
                            </div>

                            {{-- Tgl. Penetapan --}}
                            <div class="flex-1 md:w-1/6 md:text-center">
                                <span class="block md:hidden text-[9px] font-black text-slate-400 uppercase mb-1">Penetapan</span>
                                <span class="text-xs font-bold text-slate-600 search-target">
                                    {{ $doc->document_date->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="md:w-1/6 flex justify-end gap-2 border-t border-slate-50 md:border-0 pt-4 md:pt-0">
                            @if($permission->pivot->can_view)
                                <a href="{{ route('documents.preview', $doc->id) }}" 
                                   class="p-2.5 bg-white text-slate-400 border border-slate-200 rounded-xl hover:bg-maroon-900 hover:text-white transition-all shadow-sm flex items-center justify-center"
                                   title="Pratinjau">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            @endif

                            @if($permission->pivot->can_download)
                                <a href="{{ route('documents.download', $doc->id) }}" 
                                   class="p-2.5 bg-maroon-900 text-yellow-400 rounded-xl hover:bg-black transition-all shadow-sm flex items-center justify-center"
                                   title="Unduh">
                                    <i class="fas fa-download text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div id="emptyState" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center opacity-20">
                        <i class="fas fa-folder-open text-6xl mb-4 text-slate-400"></i>
                        <p class="font-black uppercase tracking-[0.3em] text-xs text-slate-500">Tidak ada dokumen tersedia</p>
                    </div>
                </div>
            @endforelse
            
            <div id="noResults" class="hidden px-8 py-20 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-slate-300 text-2xl"></i>
                    </div>
                    <p class="font-black uppercase tracking-[0.2em] text-[10px] text-slate-400">Dokumen tidak ditemukan</p>
                    <button onclick="resetSearch()" class="mt-4 text-maroon-800 font-black text-[9px] uppercase tracking-widest hover:underline">Hapus Pencarian</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.document-row');
        const noResults = document.getElementById('noResults');
        const docCountLabel = document.getElementById('docCount');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const targets = row.querySelectorAll('.search-target');
                let match = false;
                
                targets.forEach(t => {
                    if (t.textContent.toLowerCase().includes(query)) {
                        match = true;
                    }
                });

                if (match) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (visibleCount === 0 && query !== "") {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
            docCountLabel.textContent = visibleCount;
        });
    });

    function resetSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    }
</script>
@endsection