@extends('layouts.app')
@section('title', 'Registrasi Produk Hukum | Siap-HUKUM')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-maroon-900 text-gold-400 rounded-xl shadow-xl mb-4">
            <i class="fas fa-gavel text-lg"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight uppercase">
            Registrasi <span class="text-maroon-800">Dokumen</span>
        </h1>
        <p class="text-slate-500 font-medium mt-2 text-sm">Tambah produk hukum baru ke basis data Siap-HUKUM</p>
    </div>

    {{-- Alert Error --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
        <p class="text-sm font-bold text-red-700 mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Terdapat kesalahan:</p>
        <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">

            {{-- Judul --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-file-signature text-maroon-800 mr-1"></i> Judul / Nama Produk Hukum
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl transition-all font-semibold text-sm text-slate-700 placeholder:text-slate-300"
                       placeholder="Contoh: Keputusan KPU Kabupaten Pasuruan Tentang...">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-tags text-maroon-800 mr-1"></i> Kategori
                </label>
                <div class="relative">
                    <select name="category_id" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700 appearance-none cursor-pointer">
                        <option value="" disabled selected>Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Nomor Dokumen --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-hashtag text-maroon-800 mr-1"></i> Nomor Registrasi
                </label>
                <input type="text" name="document_number" value="{{ old('document_number') }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700"
                       placeholder="Contoh: 180/HK.03.1/3509/2026">
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-calendar-day text-maroon-800 mr-1"></i> Tanggal Penetapan
                </label>
                <input type="date" name="document_date" value="{{ old('document_date') }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700 appearance-none">
            </div>

            {{-- Lampiran --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-project-diagram text-maroon-800 mr-1"></i> Lampiran Dari (Opsional)
                </label>
                <div class="relative">
                    <select name="parent_id"
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700 appearance-none cursor-pointer">
                        <option value="">-- Dokumen Utama (Mandiri) --</option>
                        @foreach($parentDocuments as $pDoc)
                            <option value="{{ $pDoc->id }}" {{ old('parent_id') == $pDoc->id ? 'selected' : '' }}>
                                {{ Str::limit($pDoc->name, 40) }} ({{ $pDoc->document_number }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Upload File --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                    <i class="fas fa-file-pdf text-maroon-800 mr-1"></i> Berkas Digital
                </label>
                <label id="drop-area" 
                       class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:bg-maroon-50/20 hover:border-maroon-300 transition-all cursor-pointer group">
                    <div id="upload-placeholder" class="flex flex-col items-center text-center px-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform">
                            <i id="upload-icon" class="fas fa-upload text-maroon-800 text-sm"></i>
                        </div>
                        <p id="upload-text" class="text-xs font-bold text-slate-500">Klik untuk pilih berkas</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-semibold uppercase tracking-wider">PDF, DOCX, XLSX — Maks. 10MB</p>
                    </div>
                    <input type="file" id="file-input" name="file" required class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx">
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
            <a href="{{ route('documents.index') }}"
               class="flex-1 px-6 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all text-center">
                Batalkan
            </a>
            <button type="submit"
                    class="flex-[2] bg-maroon-900 hover:bg-black text-gold-400 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fas fa-cloud-arrow-up"></i> Simpan ke Basis Data
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('file-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const dropArea  = document.getElementById('drop-area');
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');

        if (file) {
            dropArea.classList.replace('border-slate-200', 'border-emerald-400');
            dropArea.classList.remove('bg-slate-50');
            dropArea.classList.add('bg-emerald-50/40');
            uploadText.textContent = file.name;
            uploadText.classList.replace('text-slate-500', 'text-emerald-600');
            uploadIcon.classList.replace('fa-upload', 'fa-circle-check');
            uploadIcon.classList.replace('text-maroon-800', 'text-emerald-600');
        } else {
            dropArea.classList.replace('border-emerald-400', 'border-slate-200');
            dropArea.classList.add('bg-slate-50');
            dropArea.classList.remove('bg-emerald-50/40');
            uploadText.textContent = 'Klik untuk pilih berkas';
            uploadText.classList.replace('text-emerald-600', 'text-slate-500');
            uploadIcon.classList.replace('fa-circle-check', 'fa-upload');
            uploadIcon.classList.replace('text-emerald-600', 'text-maroon-800');
        }
    });
</script>
@endsection
