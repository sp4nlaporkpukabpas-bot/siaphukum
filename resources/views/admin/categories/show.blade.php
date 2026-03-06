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

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-maroon-900 text-yellow-400 rounded-2xl shadow-2xl flex items-center justify-center text-2xl border border-white/10">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter leading-none uppercase">
                        {{ $category->name }}
                    </h1>
                    <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 text-sm">
                        <i class="fas fa-file-invoice text-maroon-800"></i>
                        Terdapat <span id="docCount">{{ $documents->count() }}</span> dokumen di kategori ini
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-end gap-3">
                {{-- Permission Badges --}}
                <div class="flex gap-2">
                    @if($permission->pivot->can_view)
                        <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-2">
                            <i class="fas fa-eye text-[10px]"></i> View Access
                        </span>
                    @endif
                    @if($permission->pivot->can_download)
                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-2">
                            <i class="fas fa-download text-[10px]"></i> Download Access
                        </span>
                    @endif
                </div>
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

    {{-- Documents Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed" id="docTable">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        <th class="px-8 py-6 w-1/2">Nama Produk Hukum</th> 
                        <th class="px-8 py-6 text-center w-1/5">Nomor</th>
                        <th class="px-8 py-6 text-center w-1/5">Tgl. Penetapan</th>
                        <th class="px-8 py-6 text-right w-1/4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-maroon-50/30 transition-all group document-row">
                            <td class="px-8 py-6">
                                <div class="flex items-start gap-4 h-full">
                                    <div class="mt-1 w-10 h-10 shrink-0 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-maroon-900 group-hover:text-yellow-400 transition-all">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 {{ !$doc->parent_id ? 'self-center' : '' }}">
                                        <p class="font-bold text-sm text-slate-800 leading-snug group-hover:text-maroon-900 transition-colors italic search-target">
                                            {{ $doc->name }}
                                        </p>
                                        
                                        @if($doc->parent_id)
                                            <div class="mt-1.5 flex flex-wrap items-baseline gap-x-2 gap-y-1">
                                                <span class="text-[9px] font-black text-maroon-700 uppercase tracking-tighter shrink-0 flex items-center gap-1">
                                                    <i class="fas fa-link text-[8px]"></i> Lampiran:
                                                </span>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px]">
                                                    <span class="text-slate-500 font-bold leading-tight search-target">
                                                        {{ $doc->parent->name }}
                                                    </span>
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        <span class="text-slate-300 text-[8px]">•</span>
                                                        <span class="font-mono bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 text-[9px] text-slate-400 whitespace-nowrap search-target">
                                                            {{ $doc->parent->document_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-8 py-6 text-center align-top">
                                <span class="inline-block bg-slate-50 px-3 py-1 rounded text-[11px] font-mono font-bold text-slate-500 border border-slate-100 whitespace-nowrap search-target">
                                    {{ $doc->document_number }}
                                </span>
                            </td>

                            <td class="px-8 py-6 text-center align-top">
                                <span class="text-xs font-bold text-slate-600 whitespace-nowrap search-target">
                                    {{ $doc->document_date->format('d M Y') }}
                                </span>
                            </td>

                            <td class="px-8 py-6 text-right align-top">
                                <div class="flex justify-end gap-2">
                                    @if($permission->pivot->can_view)
                                        {{-- SEKARANG MENGARAH KE PREVIEW --}}
                                        <a href="{{ route('documents.preview', $doc->id) }}" 
                                        class="p-2.5 bg-white text-slate-400 border border-slate-200 rounded-xl hover:bg-maroon-900 hover:text-white transition-all shadow-sm flex items-center justify-center"
                                        title="Pratinjau Keamanan">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    @endif

                                    @if($permission->pivot->can_download)
                                        <a href="{{ route('documents.download', $doc->id) }}" 
                                        class="p-2.5 bg-maroon-900 text-yellow-400 rounded-xl hover:bg-black transition-all shadow-sm flex items-center justify-center"
                                        title="Unduh Dokumen">
                                            <i class="fas fa-download text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyState">
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-folder-open text-6xl mb-4 text-slate-400"></i>
                                    <p class="font-black uppercase tracking-[0.3em] text-xs text-slate-500">Tidak ada dokumen tersedia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    
                    {{-- Row for No Results (Hidden by default) --}}
                    <tr id="noResults" class="hidden">
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-search text-slate-300 text-2xl"></i>
                                </div>
                                <p class="font-black uppercase tracking-[0.2em] text-[10px] text-slate-400">Dokumen tidak ditemukan</p>
                                <button onclick="resetSearch()" class="mt-4 text-maroon-800 font-black text-[9px] uppercase tracking-widest hover:underline">Hapus Pencarian</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                // Mencari teks di dalam elemen dengan class .search-target
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

            // Update UI berdasarkan hasil
            if (visibleCount === 0 && query !== "") {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }

            // Update counter dokumen (opsional)
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