@extends('layouts.app')
@section('title', 'Edit Dokumen | Siap-HUKUM')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-maroon-900 text-gold-400 rounded-xl shadow-xl mb-4">
            <i class="fas fa-edit text-lg"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight uppercase">
            Edit <span class="text-maroon-800">Dokumen</span>
        </h1>
        <p class="text-slate-500 font-medium mt-2 text-sm">Memperbarui: <span class="font-bold text-slate-700">{{ $document->document_number }}</span></p>
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
    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">

            {{-- Judul --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Judul Produk Hukum</label>
                <input type="text" name="name" value="{{ old('name', $document->name) }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Kategori</label>
                <div class="relative">
                    <select name="category_id" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700 appearance-none cursor-pointer">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $document->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Nomor --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nomor Registrasi</label>
                <input type="text" name="document_number" value="{{ old('document_number', $document->document_number) }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700">
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Tanggal Penetapan</label>
                <input type="date" name="document_date" value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700">
            </div>

            {{-- Lampiran --}}
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Lampiran Dari (Opsional)</label>
                <div class="relative">
                    <select name="parent_id"
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl font-semibold text-sm text-slate-700 appearance-none cursor-pointer">
                        <option value="">-- Dokumen Utama --</option>
                        @foreach($parentDocuments as $pDoc)
                            <option value="{{ $pDoc->id }}" {{ $document->parent_id == $pDoc->id ? 'selected' : '' }}>
                                {{ Str::limit($pDoc->name, 40) }}
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
                    Ganti Berkas <span class="normal-case font-semibold text-slate-400">(kosongkan jika tidak diubah)</span>
                </label>
                <label id="drop-area"
                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:bg-maroon-50/20 hover:border-maroon-300 transition-all cursor-pointer group">
                    <div class="text-center">
                        <i id="upload-icon" class="fas fa-file-arrow-up text-maroon-800 text-lg mb-1.5 group-hover:scale-110 transition-transform inline-block"></i>
                        <p id="upload-text" class="text-xs font-bold text-slate-500">Klik untuk mengganti berkas</p>
                    </div>
                    <input type="file" id="file-input" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx">
                </label>

                <div class="mt-2 flex items-center gap-2 text-xs text-slate-400 font-medium">
                    <i class="fas fa-paperclip text-slate-300"></i>
                    Berkas saat ini:
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                       class="text-maroon-700 font-bold hover:underline">
                        Lihat Dokumen <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
            <a href="{{ route('documents.index') }}"
               class="flex-1 px-6 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all text-center">
                Batal
            </a>
            <button type="submit"
                    class="flex-[2] bg-maroon-900 hover:bg-black text-gold-400 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fas fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('file-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const dropArea   = document.getElementById('drop-area');
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');

        if (file) {
            dropArea.classList.replace('border-slate-200', 'border-emerald-400');
            dropArea.classList.remove('bg-slate-50');
            dropArea.classList.add('bg-emerald-50/40');
            uploadText.textContent = file.name;
            uploadText.classList.replace('text-slate-500', 'text-emerald-600');
            uploadIcon.className = 'fas fa-circle-check text-emerald-600 text-lg mb-1.5 inline-block';
        } else {
            dropArea.classList.replace('border-emerald-400', 'border-slate-200');
            dropArea.classList.add('bg-slate-50');
            dropArea.classList.remove('bg-emerald-50/40');
            uploadText.textContent = 'Klik untuk mengganti berkas';
            uploadText.classList.replace('text-emerald-600', 'text-slate-500');
            uploadIcon.className = 'fas fa-file-arrow-up text-maroon-800 text-lg mb-1.5 inline-block';
        }
    });
</script>
@endsection
