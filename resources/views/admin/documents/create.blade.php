@extends('layouts.app')
@section('title', 'Registrasi Produk Hukum | Siap-HUKUM')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Branding Header --}}
    <div class="mb-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-maroon-900 text-gold-400 rounded-2xl shadow-2xl mb-6 border border-white/10">
            <i class="fas fa-gavel text-2xl"></i>
        </div>
        
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter leading-none uppercase">
            Siap-<span class="text-maroon-800">HUKUM</span>
        </h1>
        <div class="flex items-center justify-center gap-3 mt-3">
            <div class="h-[1px] w-8 bg-gold-400"></div>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em]">Kabupaten Pasuruan</p>
            <div class="h-[1px] w-8 bg-gold-400"></div>
        </div>
        <p class="text-slate-500 font-medium mt-4 text-sm">Registrasi Baru Dokumen & Produk Hukum</p>
    </div>

    {{-- Form Card --}}
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" 
          class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-slate-100 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-maroon-900/5 rounded-full -mr-16 -mt-16"></div>
        
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10 relative">
            
            {{-- Nama Produk Hukum --}}
            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-file-signature text-maroon-800"></i> Judul / Nama Produk Hukum
                </label>
                <input type="text" name="name" required
                       class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl transition-all font-bold text-slate-700 placeholder:text-slate-300 shadow-sm" 
                       placeholder="Contoh: Keputusan KPU Kabupaten Pasuruan Tentang...">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-tags text-maroon-800"></i> Klasifikasi Kategori
                </label>
                <div class="relative">
                    <select name="category_id" required
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 appearance-none shadow-sm cursor-pointer">
                        <option value="" disabled selected>Pilih Kategori Dokumen...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Nomor Dokumen --}}
            <div>
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-hashtag text-maroon-800"></i> Nomor Registrasi Dokumen
                </label>
                <input type="text" name="document_number" required
                       class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 shadow-sm" 
                       placeholder="Contoh: 180/HK.03.1/3509/2026">
            </div>

            {{-- Tanggal Penetapan --}}
            <div>
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-calendar-day text-maroon-800"></i> Tanggal Penetapan / Pengesahan
                </label>
                <div class="relative group">
                    <input type="date" name="document_date" required
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 shadow-sm transition-all appearance-none">
                    {{-- Kita tambahkan sedikit hint format --}}
                    <p class="text-[9px] text-slate-400 mt-2 ml-1 font-bold uppercase tracking-wider italic">Format: Hari / Bulan / Tahun</p>
                </div>
            </div>

            {{-- Relasi Parent --}}
            <div>
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-project-diagram text-maroon-800"></i> Hubungkan Sebagai Lampiran
                </label>
                <div class="relative">
                    <select name="parent_id" 
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 appearance-none shadow-sm text-sm">
                        <option value="">-- Dokumen Utama (Mandiri) --</option>
                        @foreach($parentDocuments as $pDoc)
                            <option value="{{ $pDoc->id }}">{{ Str::limit($pDoc->name, 45) }} ({{ $pDoc->document_number }})</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Upload File dengan Preview State --}}
            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <i class="fas fa-file-pdf text-maroon-800"></i> Berkas Digital (Salinan Produk Hukum)
                </label>
                <div class="relative w-full group">
                    <label id="drop-area" class="flex flex-col items-center justify-center w-full h-44 border-4 border-dashed border-slate-100 rounded-[2.5rem] bg-slate-50/50 hover:bg-maroon-50/30 hover:border-maroon-200 transition-all cursor-pointer">
                        <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 transition-all">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                <i id="upload-icon" class="fas fa-upload text-maroon-800"></i>
                            </div>
                            <p id="upload-text" class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Pilih berkas dari perangkat Anda</p>
                            <p class="text-[9px] text-slate-400 mt-2 uppercase font-black tracking-widest">Format: PDF, DOCX, XLSX (Maks. 10MB)</p>
                        </div>
                        <input type="file" id="file-input" name="file" required class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx" />
                    </label>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-12 flex flex-col md:flex-row gap-4">
            <button type="submit" 
                    class="flex-[2] bg-maroon-900 hover:bg-black text-gold-400 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-maroon-900/20 transition-all flex items-center justify-center gap-3 order-2 md:order-1">
                <i class="fas fa-cloud-check text-sm"></i> Simpan ke Basis Data Siap-HUKUM
            </button>
            <a href="{{ route('documents.index') }}" 
               class="flex-1 px-10 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center justify-center order-1 md:order-2">
                Batalkan
            </a>
        </div>
    </form>
</div>

{{-- Script untuk Handle Perubahan File --}}
<script>
    document.getElementById('file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Pilih berkas dari perangkat Anda";
        const dropArea = document.getElementById('drop-area');
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');
        const placeholder = document.getElementById('upload-placeholder');

        if (e.target.files.length > 0) {
            // Berubah menjadi mode "File Terpilih"
            dropArea.classList.remove('bg-slate-50/50', 'border-slate-100');
            dropArea.classList.add('bg-green-50/50', 'border-green-400');
            
            uploadText.innerText = "Berkas Terpilih: " + fileName;
            uploadText.classList.remove('text-slate-500');
            uploadText.classList.add('text-green-600');
            
            uploadIcon.classList.remove('fa-upload', 'text-maroon-800');
            uploadIcon.classList.add('fa-check-circle', 'text-green-600');
        } else {
            // Reset ke awal jika batal
            dropArea.classList.add('bg-slate-50/50', 'border-slate-100');
            dropArea.classList.remove('bg-green-50/50', 'border-green-400');
            uploadText.innerText = "Pilih berkas dari perangkat Anda";
            uploadIcon.classList.replace('fa-check-circle', 'fa-upload');
        }
    });
</script>
@endsection