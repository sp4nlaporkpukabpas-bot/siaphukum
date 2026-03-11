@extends('layouts.app')
@section('title', 'Dashboard Overview')

@section('content')
<div class="max-w-full">
    {{-- Header & Welcome --}}
    <div class="mb-10">
        <div class="inline-flex items-center gap-2 bg-maroon-50 text-maroon-800 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest border border-maroon-100 mb-4">
            <span class="w-2 h-2 bg-maroon-600 rounded-full animate-ping"></span>
            SIAP-HUKUM v1.0 • Live Monitoring
        </div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter">
            Selamat Datang, <span class="text-maroon-800 italic uppercase">{{ explode(' ', $userName)[0] }}</span> 👋
        </h1>
        <p class="text-slate-500 font-medium mt-2">Sistem Informasi Arsip Produk Hukum - {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>

    {{-- Statistik Cards: Menggunakan sistem 12 kolom --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-10 items-start">
        
        {{-- Card 1: Total Kategori (col-span-3) --}}
        <div class="md:col-span-3 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 group hover:shadow-xl transition-all">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Kategori</p>
            <h3 class="text-4xl font-black text-slate-900 mt-1">{{ $totalKategori }}</h3>
        </div>

        {{-- Card 2: Total Dokumen (col-span-3) --}}
        <div class="md:col-span-3 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 group hover:shadow-xl transition-all">
            <div class="w-12 h-12 bg-maroon-50 text-maroon-800 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-maroon-800 group-hover:text-white transition-all">
                <i class="fas fa-file-contract text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Dokumen Terarsip</p>
            <h3 class="text-4xl font-black text-slate-900 mt-1">{{ number_format($totalDokumen) }}</h3>
        </div>

        {{-- Card 3: Rekap Dinamis (col-span-6) --}}
        <div class="md:col-span-6 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col max-h-[300px]">
            <div class="mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rekapitulasi Terbaru</p>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar space-y-3">
                @forelse($activeRekaps as $rekap)
                    <a href="{{ $rekap->link_dokumen }}" target="_blank" 
                    class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-maroon-300 hover:bg-maroon-50 transition-all group">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-maroon-800 shrink-0 shadow-sm">
                            <i class="fas fa-file-pdf text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-[10px] font-black text-slate-900 uppercase truncate">{{ $rekap->nama_rekap }}</h4>
                            <p class="text-[9px] font-bold text-slate-400">TAHUN {{ $rekap->tahun }}</p>
                        </div>
                    </a>
                @empty
                    <div class="h-20 flex items-center justify-center text-center">
                        <p class="text-[10px] text-slate-400 uppercase">Tidak ada data</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Filter & Statistik Kategori (Grid 2 Kolom) --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="font-black text-slate-900 uppercase text-sm tracking-widest italic leading-none">Statistik Produk Hukum</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2 tracking-widest">Jumlah arsip berdasarkan kategori & periode penetapan</p>
            </div>
            
            {{-- Form Filter Periode --}}
            <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                <select name="month" onchange="this.form.submit()" class="min-w-[130px] text-[10px] font-black border-slate-100 bg-slate-50 rounded-xl focus:ring-maroon-500 uppercase py-2.5">
                    @foreach(range(1, 12) as $m)
                        @php
                            // Menggunakan Carbon untuk mendapatkan nama bulan dalam Bahasa Indonesia
                            $monthName = \Carbon\Carbon::createFromFormat('m', $m)->locale('id')->isoFormat('MMMM');
                        @endphp
                        <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="min-w-[100px] text-[10px] font-black border-slate-100 bg-slate-50 rounded-xl focus:ring-maroon-500 uppercase py-2.5">
                    @foreach(range(date('Y')-5, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        
        {{-- Grid 2 Kolom Full --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
            @foreach($categoriesStats as $cat)
            <div class="group flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 border border-slate-100 hover:border-maroon-200 hover:bg-white transition-all shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-maroon-800 rounded-full group-hover:scale-y-110 transition-transform"></div>
                    <span class="text-[11px] font-black text-slate-600 uppercase tracking-tight">{{ $cat->name }}</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-slate-900">{{ number_format($cat->documents_count) }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Dokumen</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-10">
        {{-- Tabel 1: Dokumen Terbaru Berdasarkan Penetapan --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-maroon-800 text-white rounded-lg flex items-center justify-center text-xs">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest italic leading-none">Dokumen Terbaru (Penetapan)</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Urutan berdasarkan tanggal produk hukum disahkan</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                            <th class="px-6 py-4">Nomor Dokumen</th>
                            <th class="px-6 py-4">Nama Produk Hukum</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Tgl. Penetapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentDocs as $doc)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4 text-[10px] font-mono font-bold text-maroon-800">{{ $doc->document_number }}</td>
                            <td class="px-6 py-4 font-bold text-xs text-slate-700">{{ $doc->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[9px] font-black text-slate-500 uppercase">{{ $doc->category->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-[10px] font-bold text-slate-500 italic">{{ $doc->document_date->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 text-[10px] uppercase font-black">Data Kosong</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 2: Aktivitas Upload Terbaru --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center text-xs">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest italic leading-none">Aktivitas Upload Terbaru</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 tracking-widest">Urutan berdasarkan waktu entry data ke sistem</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                            <th class="px-6 py-4">Produk Hukum</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Waktu Upload</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentUploads as $upload)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4 font-bold text-xs text-slate-700 leading-tight">
                                {{ $upload->name }} <br>
                                <span class="text-[9px] text-slate-400 font-normal uppercase tracking-tighter">{{ $upload->document_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-black uppercase">{{ $upload->category->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-slate-600">
                                    {{ $upload->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-slate-400 text-[10px] uppercase font-black">Belum ada aktivitas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection