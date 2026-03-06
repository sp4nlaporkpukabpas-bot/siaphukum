@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Daftar Pengguna</h3>
            <p class="text-sm text-slate-500">Kelola akun pegawai dan hak akses sistem.</p>
        </div>
        <button onclick="openAddModal()" class="bg-maroon-900 hover:bg-maroon-800 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-maroon-900/20">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">User & NIP</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Username</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role Aktif</th>
                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium">NIP: {{ $user->nip ?? '-' }}</div>
                    </td>
                    <td class="px-8 py-5 text-sm font-medium text-slate-600">{{ $user->username }}</td>
                    <td class="px-8 py-5">
                        <span class="bg-gold-400/10 text-gold-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                            {{ $user->activeRole->display_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 flex justify-center gap-2">
                        <button onclick="openEditModal({{ $user->toJson() }}, {{ $user->roles->pluck('id')->toJson() }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="userModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h4 id="modalTitle" class="text-xl font-bold text-slate-900">User Form</h4>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>

            <form id="userForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="in_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600/20 outline-none transition-all" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">NIP</label>
                        <input type="text" name="nip" id="in_nip" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Username</label>
                        <input type="text" name="username" id="in_username" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600/20 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="in_password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-maroon-600/20 outline-none transition-all">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-maroon-900">
                                <i id="toggleIcon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p id="passHint" class="text-[9px] text-slate-400 mt-1 italic"></p>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Role Tersedia</label>
                        <select name="roles[]" id="in_roles" multiple placeholder="Pilih beberapa role..." autocomplete="off">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 text-maroon-900">Role Default (Aktif)</label>
                        <div class="relative">
                            <select name="active_role_id" id="in_active_role" class="w-full bg-maroon-50/50 border border-maroon-100 rounded-xl px-4 py-2.5 text-sm appearance-none outline-none focus:ring-2 focus:ring-maroon-600/10 font-bold text-maroon-900" required>
                                {{-- Terisi via JS --}}
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-maroon-400 text-xs">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold text-sm transition-all text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-maroon-900 hover:bg-maroon-800 text-white font-bold text-sm shadow-lg transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Tom Select
    let roleSelect = new TomSelect('#in_roles', {
        plugins: ['remove_button'],
        onItemAdd: function() { syncActiveRole(); },
        onItemRemove: function() { syncActiveRole(); }
    });

    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const activeSelect = document.getElementById('in_active_role');
    const passInput = document.getElementById('in_password');
    const passHint = document.getElementById('passHint');
    const toggleIcon = document.getElementById('toggleIcon');

    function togglePassword() {
        if (passInput.type === "password") {
            passInput.type = "text";
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = "password";
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function syncActiveRole(savedActiveId = null) {
        const selectedIds = roleSelect.getValue(); 
        const currentActiveValue = activeSelect.value;
        activeSelect.innerHTML = '';

        if (selectedIds.length > 0) {
            selectedIds.forEach((roleId, index) => {
                const originalOption = document.querySelector(`#in_roles option[value="${roleId}"]`);
                const option = document.createElement('option');
                option.value = roleId;
                option.text = originalOption.text;
                
                // Prioritas selection: 
                // 1. savedActiveId (saat baru buka modal edit)
                // 2. currentActiveValue (saat sedang bongkar pasang role di modal)
                // 3. index 0 (saat baru tambah atau role sebelumnya dihapus)
                if (savedActiveId && roleId == savedActiveId) {
                    option.selected = true;
                } else if (!savedActiveId && roleId == currentActiveValue) {
                    option.selected = true;
                } else if (!savedActiveId && !currentActiveValue && index === 0) {
                    option.selected = true;
                }
                
                activeSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.text = 'Pilih role terlebih dahulu';
            activeSelect.appendChild(option);
        }
    }

    function openAddModal() {
        form.reset();
        roleSelect.clear();
        document.getElementById('modalTitle').innerText = "Tambah User Baru";
        document.getElementById('methodField').innerHTML = "";
        
        passInput.type = "password";
        passInput.value = "12345678";
        passHint.innerText = "*Default: 12345678. Klik mata untuk melihat.";
        
        form.action = "{{ route('users.store') }}";
        syncActiveRole();
        modal.classList.remove('hidden');
    }

    function openEditModal(user, userRoles) {
        form.reset();
        document.getElementById('modalTitle').innerText = "Edit Data User";
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        passInput.type = "password";
        passInput.value = "";
        passHint.innerText = "*Kosongkan jika tidak ingin mengubah password.";
        
        form.action = `/admin/users/${user.id}`;
        document.getElementById('in_name').value = user.name;
        document.getElementById('in_nip').value = user.nip;
        document.getElementById('in_username').value = user.username;
        
        roleSelect.setValue(userRoles);
        syncActiveRole(user.active_role_id);

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
        roleSelect.clear();
        toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        passInput.type = "password";
    }
</script>
@endsection