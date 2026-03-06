@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Hak Akses</h3>
            <p class="text-sm text-slate-500 mt-1">Mengatur nama tampilan peran dalam sistem.</p>
        </div>
        <div class="bg-maroon-50 text-maroon-600 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider">
            Sistem Terkunci (Non-Deletable)
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Name (System)</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Display Name (User)</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($roles as $role)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <code class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono">{{ $role->name }}</code>
                    </td>
                    <td class="px-8 py-5 text-sm font-semibold text-slate-700">
                        {{ $role->display_name }}
                    </td>
                    <td class="px-8 py-5 text-center">
                        <button onclick="openEditModal({{ $role->id }}, '{{ $role->display_name }}')" 
                                class="inline-flex items-center gap-2 bg-gold-400/10 hover:bg-gold-400 text-gold-500 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all">
                            <i class="fas fa-edit"></i> Ubah Nama
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 overflow-hidden">
            <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-3">
                <i class="fas fa-shield-halved text-maroon-600"></i> Ubah Display Name
            </h4>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Tampilan Baru</label>
                        <input type="text" name="display_name" id="modal_display_name" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-maroon-600/20 focus:border-maroon-600 transition-all">
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 text-sm font-bold hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 rounded-xl bg-maroon-900 text-white text-sm font-bold hover:bg-maroon-800 shadow-lg shadow-maroon-900/20 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, currentName) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const input = document.getElementById('modal_display_name');
        
        form.action = `/admin/roles/${id}`; // Sesuaikan dengan path route Anda
        input.value = currentName;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection