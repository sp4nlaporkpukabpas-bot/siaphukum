@extends('layouts.app')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="max-w-full">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter">
                Kelola <span class="text-maroon-800 italic">Kategori</span>
            </h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Atur kategori dokumen dan delegasi hak akses per role.</p>
        </div>
        <button onclick="openAddModal()"
                class="inline-flex items-center justify-center gap-2 bg-maroon-800 hover:bg-maroon-900 text-white px-5 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shrink-0">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-center hidden sm:table-cell">Izin Role</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50/70 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-maroon-50 text-maroon-800 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-maroon-800 group-hover:text-white transition-all">
                                    <i class="fas fa-folder text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-800">{{ $cat->name }}</p>
                                    {{-- Mobile: tampilkan badge di bawah nama --}}
                                    <div class="flex flex-wrap gap-1 mt-1 sm:hidden">
                                        @foreach($cat->roles as $role)
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[9px] font-bold text-slate-500 border border-slate-200">
                                                {{ $role->display_name }}
                                                <span class="text-maroon-700">
                                                    {{ $role->pivot->can_view ? 'V' : '' }}{{ $role->pivot->can_download ? 'D' : '' }}
                                                </span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <div class="flex flex-wrap justify-center gap-1.5">
                                @foreach($cat->roles as $role)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-[9px] font-bold text-slate-600 border border-slate-200">
                                        {{ $role->display_name }}
                                        @if($role->pivot->can_view)
                                            <span class="text-blue-500" title="View"><i class="fas fa-eye text-[8px]"></i></span>
                                        @endif
                                        @if($role->pivot->can_download)
                                            <span class="text-emerald-500" title="Download"><i class="fas fa-download text-[8px]"></i></span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-1.5">
                                <button onclick="openEditModal({{ $cat->toJson() }}, {{ $cat->roles->toJson() }})"
                                        class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all"
                                        title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori ini? Semua dokumen terkait mungkin terpengaruh.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all"
                                            title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-16 text-center">
                            <i class="fas fa-folder-open text-slate-200 text-4xl mb-3 block"></i>
                            <p class="text-slate-400 font-medium text-sm">Belum ada kategori. Tambah kategori pertama Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-3 bg-slate-50/50">
            <p class="text-xs text-slate-400 font-medium">Total: <span class="font-black text-slate-600">{{ $categories->count() }}</span> kategori</p>
        </div>
    </div>
</div>

{{-- ── MODAL TAMBAH/EDIT KATEGORI ─────────────────────────────────────────── --}}
<div id="categoryModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 md:p-8 text-slate-900">

            <div class="flex items-center justify-between mb-6">
                <h4 id="modalTitle" class="text-base font-black text-slate-900 uppercase tracking-tight">Tambah Kategori</h4>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-all">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form id="categoryForm" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="space-y-5">
                    {{-- Nama Kategori --}}
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                        <input type="text" name="name" id="in_name" required
                               class="w-full bg-slate-50 border-2 border-transparent focus:border-maroon-800 focus:bg-white rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition-all"
                               placeholder="Contoh: Keputusan KPU">
                    </div>

                    {{-- Matriks Izin --}}
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Matriks Izin Akses per Role</label>
                        <div class="border border-slate-100 rounded-xl overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-2.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                                        <th class="px-4 py-2.5 text-center text-[10px] font-black text-blue-400 uppercase tracking-widest">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </th>
                                        <th class="px-4 py-2.5 text-center text-[10px] font-black text-emerald-400 uppercase tracking-widest">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($roles as $role)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-semibold text-sm text-slate-700">{{ $role->display_name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $role->id }}][can_view]"
                                                   id="view_{{ $role->id }}"
                                                   class="w-4 h-4 rounded accent-blue-600 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox"
                                                   name="permissions[{{ $role->id }}][can_download]"
                                                   id="dl_{{ $role->id }}"
                                                   class="w-4 h-4 rounded accent-emerald-600 cursor-pointer">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Role yang tidak dicentang tidak akan dapat mengakses kategori ini.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 mt-7">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 font-black text-xs uppercase tracking-widest text-slate-500 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-[2] py-3 rounded-xl bg-maroon-900 hover:bg-black text-gold-400 font-black text-xs uppercase tracking-widest shadow-lg transition-all">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('categoryModal');
    const form  = document.getElementById('categoryForm');

    function openAddModal() {
        form.reset();
        document.getElementById('modalTitle').innerText = 'Tambah Kategori Baru';
        document.getElementById('methodField').innerHTML = '';
        form.action = "{{ route('categories.store') }}";
        modal.classList.remove('hidden');
    }

    function openEditModal(cat, catRoles) {
        form.reset();
        document.getElementById('modalTitle').innerText = 'Edit Kategori & Izin';
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        form.action = `/master/categories/${cat.id}`;
        document.getElementById('in_name').value = cat.name;

        catRoles.forEach(role => {
            const viewEl = document.getElementById(`view_${role.id}`);
            const dlEl   = document.getElementById(`dl_${role.id}`);
            if (viewEl) viewEl.checked = !!role.pivot.can_view;
            if (dlEl)   dlEl.checked   = !!role.pivot.can_download;
        });

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    // Tutup modal dengan ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection
