@extends('layouts.app')
@section('title', 'Master Dokumen')

@section('content')
<div class="max-w-full">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Database <span class="text-maroon-800 italic">Dokumen</span></h1>
            <p class="text-slate-500 font-medium">Manajemen seluruh arsip produk hukum Pasuruan.</p>
        </div>
        
        <div class="flex items-center gap-4">
            {{-- Input Search Baru --}}
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                </span>
                <input type="text" id="searchInput" 
                    class="block w-64 pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-maroon-800 focus:border-maroon-800 text-sm font-medium transition-all outline-none" 
                    placeholder="Cari dokumen...">
            </div>

            <a href="{{ route('documents.create') }}" class="bg-maroon-800 hover:bg-maroon-900 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl flex items-center gap-3">
                <i class="fas fa-upload"></i> Unggah Dokumen Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="documentTable">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Info Dokumen</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5">No. Dokumen</th>
                        <th class="px-8 py-5">Tanggal</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/50 transition-all group document-row">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-red-600 group-hover:text-white transition-all">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    {{-- Class 'doc-name' untuk target search --}}
                                    <p class="font-bold text-sm text-slate-700 leading-none doc-name">{{ $doc->name }}</p>
                                    @if($doc->parent_id)
                                        <span class="text-[9px] font-black text-maroon-600 uppercase italic">Lampiran dari: {{ $doc->parent->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            {{-- Class 'doc-category' untuk target search --}}
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase doc-category">{{ $doc->category->name }}</span>
                        </td>
                        <td class="px-8 py-5 font-mono text-xs text-slate-500 doc-number">{{ $doc->document_number }}</td>
                        <td class="px-8 py-5 text-xs font-bold text-slate-600">{{ $doc->document_date->format('d M Y') }}</td>
                        <td class="px-8 py-5">
                            <div class="flex justify-end gap-2">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('documents.edit', $doc->id) }}" class="w-9 h-9 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada dokumen yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- Elemen Not Found (Default hidden) --}}
            <div id="noResult" class="hidden p-10 text-center">
                <i class="fas fa-search text-slate-200 text-5xl mb-4"></i>
                <p class="text-slate-500 font-medium">Dokumen tidak ditemukan.</p>
            </div>
        </div>
    </div>
</div>

{{-- Skrip Vanilla JS untuk Search --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const noResult = document.getElementById('noResult');
        const rows = document.querySelectorAll('.document-row');

        searchInput.addEventListener('keyup', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let foundCount = 0;

            rows.forEach(row => {
                // Mencari di Nama, Kategori, dan Nomor Dokumen
                const name = row.querySelector('.doc-name').innerText.toLowerCase();
                const category = row.querySelector('.doc-category').innerText.toLowerCase();
                const number = row.querySelector('.doc-number').innerText.toLowerCase();

                if (name.includes(query) || category.includes(query) || number.includes(query)) {
                    row.style.display = ""; // Tampilkan baris
                    foundCount++;
                } else {
                    row.style.display = "none"; // Sembunyikan baris
                }
            });

            // Tampilkan pesan "Tidak ditemukan" jika pencarian gagal
            if (foundCount === 0 && query !== "") {
                noResult.classList.remove('hidden');
            } else {
                noResult.classList.add('hidden');
            }
        });
    });
</script>
@endsection