@extends('layouts.app')
@section('title', 'Rekapitulasi Arsip')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center text-slate-900">
        <div>
            <h3 class="text-xl font-bold">Rekapitulasi Arsip
            <p class="text-sm text-slate-500">Kelola daftar rekapitulasi arsip hukum.</p>
        </div>
        <button onclick="openAddModal()" class="bg-maroon-900 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-maroon-900/20">
            <i class="fas fa-plus mr-2"></i> Tambah Rekap
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Rekap</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tahun</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rekaps as $rekap)
                <tr>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-900">{{ $rekap->nama_rekap }}</span>
                            <a href="{{ $rekap->link_dokumen }}" target="_blank" class="text-blue-500 text-xs hover:underline mt-1">
                                <i class="fas fa-external-link-alt mr-1"></i> Lihat Rekap
                            </a>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center font-semibold text-slate-600">{{ $rekap->tahun }}</td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-3 py-1 rounded-full {{ $rekap->is_visible ? 'bg-green-500/10 text-green-600' : 'bg-slate-100 text-slate-400' }} text-[10px] font-black uppercase">
                            {{ $rekap->is_visible ? 'Muncul' : 'Sembunyi' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 flex justify-center gap-2">
                        {{-- Menggunakan base64 encode untuk menghindari masalah kutipan di JSON --}}
                        <button onclick="openEditModal({{ json_encode($rekap) }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('rekap-register.destroy', $rekap->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 italic text-sm">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL --}}
<div id="rekapModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl w-full max-w-lg p-8">
            <h4 id="modalTitle" class="text-xl font-bold mb-6">Tambah Rekap</h4>
            <form id="rekapForm" method="POST">
                @csrf
                <div id="methodWrapper"></div>
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nama Rekap</label>
                        <input type="text" name="nama_rekap" id="in_nama_rekap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Tahun</label>
                            <input type="number" name="tahun" id="in_tahun" value="{{ date('Y') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Status</label>
                            <select name="is_visible" id="in_is_visible" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none">
                                <option value="1">Munculkan</option>
                                <option value="0">Sembunyikan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Link Dokumen</label>
                        <input type="url" name="link_dokumen" id="in_link_dokumen" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl bg-slate-100 font-bold text-sm">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-maroon-900 text-white font-bold text-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('rekapForm').reset();
        document.getElementById('modalTitle').innerText = "Tambah Rekap Baru";
        document.getElementById('methodWrapper').innerHTML = "";
        // Menggunakan helper route yang benar
        document.getElementById('rekapForm').action = "{{ route('rekap-register.store') }}";
        document.getElementById('rekapModal').classList.remove('hidden');
    }

    function openEditModal(rekap) {
        document.getElementById('modalTitle').innerText = "Edit Rekap";
        document.getElementById('methodWrapper').innerHTML = '@method("PUT")';
        
        let url = "{{ route('rekap-register.update', ':id') }}";
        document.getElementById('rekapForm').action = url.replace(':id', rekap.id);
        
        document.getElementById('in_nama_rekap').value = rekap.nama_rekap;
        document.getElementById('in_tahun').value = rekap.tahun;
        document.getElementById('in_link_dokumen').value = rekap.link_dokumen;
        
        // PERBAIKAN DI SINI:
        // Pastikan nilai dikonversi menjadi string '1' atau '0'
        // agar cocok dengan value pada tag <option>
        document.getElementById('in_is_visible').value = rekap.is_visible ? "1" : "0";
        
        document.getElementById('rekapModal').classList.remove('hidden');
    }

    function closeModal() { document.getElementById('rekapModal').classList.add('hidden'); }
</script>
@endsection