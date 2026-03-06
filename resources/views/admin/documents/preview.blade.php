@extends('layouts.app')
@section('title', 'Keamanan Dokumen: ' . $document->name)

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-maroon-900 text-yellow-400 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight leading-none">{{ $document->name }}</h1>
                <p class="text-[10px] text-maroon-700 font-bold uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    Mode Pratinjau Terbatas (Anti-Unduh)
                </p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Arsip
        </a>
    </div>

    {{-- Frame Viewer --}}
    <div class="relative bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border-[8px] border-slate-800 h-[75vh]">
        {{-- Invisible Shield: Mencegah klik kanan & seleksi di area atas --}}
        <div class="absolute top-0 left-0 w-full h-16 z-20" oncontextmenu="return false;"></div>
        
        <iframe 
            src="{{ route('documents.view-secure', $document->id) }}#toolbar=0&navpanes=0&scrollbar=0" 
            class="w-full h-full border-none select-none"
            style="pointer-events: fill;"
            oncontextmenu="return false;">
        </iframe>
    </div>

    <div class="mt-6 flex justify-center italic">
        <p class="text-[10px] text-slate-400 font-medium bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
            Pencetakan dan pengunduhan dinonaktifkan secara sistem untuk keamanan dokumen negara.
        </p>
    </div>
</div>

{{-- Script Anti-Shortcut --}}
<script>
    document.addEventListener('keydown', function(e) {
        // Blokir Ctrl+S (Save), Ctrl+P (Print), Ctrl+U (View Source), Ctrl+Shift+I (Inspect)
        if ((e.ctrlKey || e.metaKey) && (['s', 'p', 'u'].includes(e.key.toLowerCase()))) {
            e.preventDefault();
            alert('Aksi ini dibatasi untuk keamanan dokumen.');
        }
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
        }
    });

    // Mencegah Klik Kanan di seluruh halaman preview
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
@endsection