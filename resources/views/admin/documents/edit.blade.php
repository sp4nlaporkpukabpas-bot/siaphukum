@extends('layouts.app')
@section('title', 'Edit Dokumen | Siap-HUKUM')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-maroon-900 text-gold-400 rounded-2xl shadow-2xl mb-6 border border-white/10">
            <i class="fas fa-edit text-2xl"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter leading-none uppercase">
            Edit <span class="text-maroon-800">Dokumen</span>
        </h1>
        <p class="text-slate-500 font-medium mt-4 text-sm">Memperbarui Data: {{ $document->document_number }}</p>
    </div>

    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" 
          class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-slate-100 relative overflow-hidden">
        
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
            {{-- Judul --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Judul Produk Hukum</label>
                <input type="text" name="name" value="{{ old('name', $document->name) }}" required
                       class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Kategori</label>
                <select name="category_id" required class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 appearance-none">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $document->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nomor Dokumen --}}
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Nomor Registrasi</label>
                <input type="text" name="document_number" value="{{ old('document_number', $document->document_number) }}" required
                       class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700">
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tanggal Penetapan</label>
                <input type="date" name="document_date" value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" required
                       class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700">
            </div>

            {{-- Parent/Lampiran --}}
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Lampiran Dari (Opsional)</label>
                <select name="parent_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-2xl font-bold text-slate-700 appearance-none">
                    <option value="">-- Dokumen Utama --</option>
                    @foreach($parentDocuments as $pDoc)
                        <option value="{{ $pDoc->id }}" {{ $document->parent_id == $pDoc->id ? 'selected' : '' }}>{{ $pDoc->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Upload File --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Ganti Berkas (Kosongkan jika tidak diubah)</label>
                <label id="drop-area" class="flex flex-col items-center justify-center w-full h-32 border-4 border-dashed border-slate-100 rounded-[2.5rem] bg-slate-50/50 hover:bg-maroon-50/30 transition-all cursor-pointer">
                    <div id="upload-placeholder" class="text-center">
                        <i id="upload-icon" class="fas fa-file-pdf text-maroon-800 mb-2"></i>
                        <p id="upload-text" class="text-xs font-bold text-slate-500 uppercase">Klik untuk mengganti file</p>
                    </div>
                    <input type="file" id="file-input" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx" />
                </label>
                <div class="mt-3 flex items-center gap-2 text-[10px] text-slate-400 font-bold">
                    <i class="fas fa-info-circle"></i>
                    <span>File saat ini: <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-maroon-800 underline">Lihat Dokumen</a></span>
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-[2] bg-maroon-900 hover:bg-black text-gold-400 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl transition-all">
                Simpan Perubahan
            </button>
            <a href="{{ route('documents.index') }}" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Klik untuk mengganti file";
        document.getElementById('upload-text').innerText = fileName;
        document.getElementById('drop-area').classList.add('border-maroon-800', 'bg-maroon-50/50');
    });
</script>
@endsection