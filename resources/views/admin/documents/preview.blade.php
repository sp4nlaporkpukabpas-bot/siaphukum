@extends('layouts.app')
@section('title', 'Pratinjau: ' . $document->name)

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-start sm:items-center gap-3">
            <div class="w-10 h-10 bg-maroon-900 text-yellow-400 rounded-xl flex items-center justify-center shadow-md shrink-0">
                <i class="fas fa-shield-check"></i>
            </div>
            <div class="min-w-0">
                <h1 class="text-base font-black text-slate-900 uppercase tracking-tight leading-tight truncate">{{ $document->name }}</h1>
                <p class="text-[10px] text-maroon-700 font-bold uppercase tracking-widest mt-1 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                    Mode Pratinjau Terbatas
                </p>
            </div>
        </div>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Info Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor</p>
            <p class="text-xs font-bold text-slate-700 mt-1 font-mono">{{ $document->document_number }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
            <p class="text-xs font-bold text-slate-700 mt-1">{{ $document->document_date->translatedFormat('d F Y') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm col-span-2 sm:col-span-1">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</p>
            <p class="text-xs font-bold text-slate-700 mt-1">{{ $document->category->name }}</p>
        </div>
    </div>

    {{-- Viewer Frame --}}
    <div class="relative bg-slate-800 rounded-2xl overflow-hidden shadow-xl border-4 border-slate-700"
         style="height: clamp(400px, 70vh, 800px);">

        {{-- Shield overlay (top) --}}
        <div class="absolute top-0 left-0 w-full h-14 z-20" oncontextmenu="return false;"></div>

        <iframe
            src="{{ route('documents.view-secure', $document->id) }}#toolbar=0&navpanes=0&scrollbar=0"
            class="w-full h-full border-none"
            style="pointer-events: fill;"
            oncontextmenu="return false;">
        </iframe>
    </div>

    <div class="mt-4 text-center">
        <p class="text-[10px] text-slate-400 font-medium bg-slate-50 inline-block px-4 py-2 rounded-full border border-slate-100">
            <i class="fas fa-lock text-slate-300 mr-1"></i>
            Pencetakan dan pengunduhan dinonaktifkan untuk keamanan dokumen.
        </p>
    </div>
</div>

<script>
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && ['s', 'p', 'u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            alert('Aksi ini dibatasi untuk keamanan dokumen.');
        }
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
        }
    });
    document.addEventListener('contextmenu', e => e.preventDefault());
</script>
@endsection
