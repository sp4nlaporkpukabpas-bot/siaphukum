@extends('layouts.app')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center text-slate-900">
        <div>
            <h3 class="text-xl font-bold">Kategori Dokumen</h3>
            <p class="text-sm text-slate-500">Kelola kategori dan delegasi hak akses role.</p>
        </div>
        <button onclick="openAddModal()" class="bg-maroon-900 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-maroon-900/20">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Izin Role</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($categories as $cat)
                <tr>
                    <td class="px-8 py-5 font-bold text-slate-900">{{ $cat->name }}</td>
                    <td class="px-8 py-5">
                        <div class="flex flex-wrap justify-center gap-1">
                            @foreach($cat->roles as $role)
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-[9px] font-bold text-slate-600 border border-slate-200">
                                    {{ $role->display_name }} ({{ $role->pivot->can_view ? 'V' : '' }}{{ $role->pivot->can_download ? 'D' : '' }})
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-8 py-5 flex justify-center gap-2">
                        <button onclick="openEditModal({{ $cat->toJson() }}, {{ $cat->roles->toJson() }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="categoryModal" class="fixed inset-0 z-[60] hidden overflow-y-auto text-slate-900">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-8">
            <h4 id="modalTitle" class="text-xl font-bold mb-6">Tambah Kategori</h4>
            <form id="categoryForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nama Kategori</label>
                        <input type="text" name="name" id="in_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-maroon-600/20 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-4">Matriks Izin Akses Role</label>
                        <div class="border border-slate-100 rounded-2xl overflow-hidden text-slate-900">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-bold text-[10px]">Role</th>
                                        <th class="px-4 py-2 text-center font-bold text-[10px]">View</th>
                                        <th class="px-4 py-2 text-center font-bold text-[10px]">Download</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($roles as $role)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $role->display_name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="permissions[{{ $role->id }}][can_view]" id="view_{{ $role->id }}" class="rounded text-maroon-900 focus:ring-maroon-900/20">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="permissions[{{ $role->id }}][can_download]" id="dl_{{ $role->id }}" class="rounded text-maroon-900 focus:ring-maroon-900/20">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl bg-slate-100 font-bold text-sm">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-maroon-900 text-white font-bold text-sm shadow-lg">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');

    function openAddModal() {
        form.reset();
        document.getElementById('modalTitle').innerText = "Tambah Kategori Baru";
        document.getElementById('methodField').innerHTML = "";
        form.action = "{{ route('categories.store') }}";
        modal.classList.remove('hidden');
    }

    function openEditModal(cat, catRoles) {
        form.reset();
        document.getElementById('modalTitle').innerText = "Edit Kategori & Izin";
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        form.action = `/master/categories/${cat.id}`;
        document.getElementById('in_name').value = cat.name;

        // Reset & Fill Checkboxes
        catRoles.forEach(role => {
            if(role.pivot.can_view) document.getElementById(`view_${role.id}`).checked = true;
            if(role.pivot.can_download) document.getElementById(`dl_${role.id}`).checked = true;
        });

        modal.classList.remove('hidden');
    }

    function closeModal() { modal.classList.add('hidden'); }
</script>
@endsection