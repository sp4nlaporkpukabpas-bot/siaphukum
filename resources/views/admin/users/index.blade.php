@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')

{{-- ============================================================
     FLASH NOTIFICATION (success / error dari session)
     ============================================================ --}}
@if(session('success') || session('error') || $errors->any())
<div id="flashNotif"
     class="fixed top-6 right-6 z-[100] flex items-start gap-3 px-5 py-4 rounded-2xl shadow-xl max-w-sm
            {{ $errors->any() || session('error') ? 'bg-red-600' : 'bg-emerald-600' }}
            text-white animate-slide-in">
    <i class="fas {{ $errors->any() || session('error') ? 'fa-circle-xmark' : 'fa-circle-check' }} text-lg mt-0.5 shrink-0"></i>
    <div>
        @if($errors->any())
            <p class="font-bold text-sm">Terdapat kesalahan input:</p>
            <ul class="text-xs mt-1 list-disc list-inside opacity-90 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @elseif(session('error'))
            <p class="font-bold text-sm">{{ session('error') }}</p>
        @else
            <p class="font-bold text-sm">{{ session('success') }}</p>
        @endif
    </div>
    <button onclick="document.getElementById('flashNotif').remove()" class="ml-2 opacity-70 hover:opacity-100 transition-opacity shrink-0">
        <i class="fas fa-times text-xs"></i>
    </button>
</div>
@endif

{{-- ============================================================
     TABEL UTAMA
     ============================================================ --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h3 class="text-xl font-bold text-slate-900">Daftar Pengguna</h3>
            <p class="text-sm text-slate-500 mt-0.5">Kelola akun pegawai dan hak akses sistem.</p>
        </div>
        <button onclick="openAddModal()"
                class="inline-flex items-center gap-2 bg-maroon-900 hover:bg-maroon-800 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-maroon-900/20 whitespace-nowrap">
            <i class="fas fa-plus text-xs"></i> Tambah User
        </button>
    </div>

    {{-- Search bar --}}
    <div class="px-6 sm:px-8 py-4 border-b border-slate-100 bg-slate-50/40">
        <div class="relative max-w-sm">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="tableSearch" placeholder="Cari nama, NIP, atau username..."
                   oninput="filterTable(this.value)"
                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-maroon-600/20 transition-all">
        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left" id="userTable">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-8">#</th>
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">User & NIP</th>
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:table-cell">Username</th>
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role Aktif</th>
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Semua Role</th>
                    <th class="px-6 sm:px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="userTableBody">
                @forelse($users as $i => $user)
                <tr class="hover:bg-slate-50/70 transition-colors user-row"
                    data-search="{{ strtolower($user->name . ' ' . ($user->nip ?? '') . ' ' . $user->username) }}">
                    <td class="px-6 sm:px-8 py-5 text-xs font-bold text-slate-400">{{ $i + 1 }}</td>
                    <td class="px-6 sm:px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-maroon-900/10 text-maroon-900 flex items-center justify-center text-xs font-black shrink-0 uppercase">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">NIP: {{ $user->nip ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 sm:px-8 py-5 text-sm font-mono font-medium text-slate-500 hidden sm:table-cell">
                        {{ $user->username }}
                    </td>
                    <td class="px-6 sm:px-8 py-5">
                        @if($user->activeRole)
                            <span class="inline-flex items-center gap-1.5 bg-maroon-900/10 text-maroon-900 px-2.5 py-1 rounded-full text-[10px] font-black uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-maroon-900 inline-block"></span>
                                {{ $user->activeRole->display_name }}
                            </span>
                        @else
                            <span class="text-slate-400 text-xs italic">—</span>
                        @endif
                    </td>
                    <td class="px-6 sm:px-8 py-5">
                        <div class="flex flex-wrap gap-1">
                            @foreach($user->roles as $role)
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-semibold">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 sm:px-8 py-5">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="openEditModal({{ $user->toJson() }}, {{ $user->roles->pluck('id')->toJson() }})"
                                    title="Edit User"
                                    class="p-2 text-blue-500 hover:bg-blue-50 active:scale-95 rounded-lg transition-all">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    title="Hapus User"
                                    class="p-2 text-red-400 hover:bg-red-50 active:scale-95 rounded-lg transition-all">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <i class="fas fa-users text-3xl opacity-30"></i>
                            <p class="text-sm font-medium">Belum ada pengguna terdaftar.</p>
                            <button onclick="openAddModal()" class="text-maroon-900 text-xs font-bold hover:underline">+ Tambah sekarang</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div id="noSearchResult" class="hidden px-8 py-12 text-center">
            <p class="text-slate-400 text-sm"><i class="fas fa-search mr-2 opacity-50"></i>Tidak ada hasil untuk pencarian ini.</p>
        </div>
    </div>

    {{-- Footer count --}}
    <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/40">
        <p class="text-xs text-slate-400">
            Total <span id="visibleCount" class="font-bold text-slate-600">{{ $users->count() }}</span> pengguna terdaftar.
        </p>
    </div>
</div>


{{-- ============================================================
     MODAL: TAMBAH / EDIT USER
     PENTING: id="modalUser" (bukan "userModal") agar tidak
     bentrok dengan window.userModal yang di-expose browser.
     ============================================================ --}}
<div id="modalUser" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6 sm:p-8 animate-modal-in">

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 id="modalTitle" class="text-lg font-bold text-slate-900">User Form</h4>
                    <p id="modalSubtitle" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
                <button onclick="closeModal()" class="text-slate-300 hover:text-slate-600 transition-colors p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="userForm" method="POST" novalidate>
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2 sm:col-span-1">
                        <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="in_name" class="form-input" placeholder="cth: Budi Santoso">
                        <p class="form-error hidden" id="err_name"></p>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" id="in_nip" class="form-input" placeholder="Opsional">
                        <p class="form-error hidden" id="err_nip"></p>
                    </div>

                    <div>
                        <label class="form-label">Username <span class="text-red-400">*</span></label>
                        <input type="text" name="username" id="in_username" class="form-input" placeholder="cth: budi.santoso" autocomplete="off">
                        <p class="form-error hidden" id="err_username"></p>
                    </div>

                    <div>
                        <label class="form-label">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="in_password" class="form-input pr-10" placeholder="Min. 6 karakter" autocomplete="new-password">
                            <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-maroon-900 transition-colors">
                                <i id="toggleIcon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p id="passHint" class="text-[9px] text-slate-400 mt-1 italic leading-snug"></p>
                        <p class="form-error hidden" id="err_password"></p>
                    </div>

                    <div class="col-span-2">
                        <label class="form-label">Role Tersedia <span class="text-red-400">*</span></label>
                        <select name="roles[]" id="in_roles" multiple placeholder="Pilih satu atau lebih role..." autocomplete="off">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        <p class="form-error hidden" id="err_roles"></p>
                    </div>

                    <div class="col-span-2">
                        <label class="form-label text-maroon-900">
                            <i class="fas fa-star text-[8px] mr-1 text-maroon-400"></i>
                            Role Default / Aktif <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <select name="active_role_id" id="in_active_role"
                                    class="w-full bg-maroon-50/60 border border-maroon-100 rounded-xl px-4 py-2.5 text-sm appearance-none outline-none focus:ring-2 focus:ring-maroon-600/15 font-semibold text-maroon-900 transition-all">
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-maroon-400 text-xs">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1 italic">Role yang digunakan saat pertama kali login.</p>
                        <p class="form-error hidden" id="err_active_role"></p>
                    </div>
                </div>

                <div class="flex gap-3 mt-7">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 active:scale-95 font-bold text-sm transition-all text-slate-600">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="flex-1 py-2.5 rounded-xl bg-maroon-900 hover:bg-maroon-800 active:scale-95 text-white font-bold text-sm shadow-lg shadow-maroon-900/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save text-xs"></i>
                        <span id="submitText">Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL: KONFIRMASI HAPUS
     id="modalDelete" (bukan "deleteModal") — alasan sama.
     ============================================================ --}}
<div id="modalDelete" class="fixed inset-0 z-[70] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-7 text-center animate-modal-in">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-triangle-exclamation text-red-500 text-xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-900 mb-1">Hapus Pengguna?</h4>
            <p class="text-sm text-slate-500 mb-1">Anda akan menghapus akun:</p>
            <p id="deleteTargetName" class="text-sm font-bold text-red-600 mb-4">—</p>
            <p class="text-xs text-slate-400 mb-6 bg-red-50 border border-red-100 rounded-xl p-3">
                Tindakan ini <strong>tidak dapat dibatalkan</strong>. Semua data terkait akun ini akan ikut terhapus.
            </p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 active:scale-95 font-bold text-sm transition-all text-slate-600">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 active:scale-95 text-white font-bold text-sm shadow-lg shadow-red-500/20 transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ============================================================
     STYLES
     ============================================================ --}}
<style>
    .form-label {
        @apply block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5;
    }
    .form-input {
        @apply w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none
               focus:ring-2 focus:ring-maroon-600/20 focus:border-maroon-300 transition-all;
    }
    .form-input.is-invalid {
        @apply border-red-300 bg-red-50/40 focus:ring-red-500/20 focus:border-red-400;
    }
    .form-error {
        @apply text-[10px] text-red-500 mt-1 font-medium;
    }
    @keyframes slide-in {
        from { opacity: 0; transform: translateX(1rem); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes modal-in {
        from { opacity: 0; transform: translateY(1rem) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-slide-in { animation: slide-in 0.3s ease; }
    .animate-modal-in { animation: modal-in 0.25s ease; }
</style>


{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script>
// ---------------------------------------------------------------
// ROOT CAUSE FIX:
// Browser secara otomatis men-expose elemen dengan "id" sebagai
// properti global window. Jadi jika ada <div id="userModal"> maka
// window.userModal sudah ada. Ketika kita tulis:
//   const userModal = document.getElementById('userModal');
// JavaScript (mode strict / TDZ) melempar ReferenceError karena
// const di-hoist ke scope tapi belum terinisialisasi saat
// fungsi lain (yang dipanggil via onclick) mencoba mengaksesnya.
//
// Solusi: ganti id HTML dan nama variabel JS agar tidak tabrakan.
//   <div id="modalUser">   → elUserModal
//   <div id="modalDelete"> → elDeleteModal
//   <form id="userForm">   → elUserForm   (form = reserved-ish)
// ---------------------------------------------------------------

// Tom Select
let roleSelect = new TomSelect('#in_roles', {
    plugins: ['remove_button'],
    onItemAdd:    () => syncActiveRole(),
    onItemRemove: () => syncActiveRole(),
});

// DOM refs — semua pakai prefix "el" supaya bebas konflik
const elUserModal    = document.getElementById('modalUser');
const elDeleteModal  = document.getElementById('modalDelete');
const elUserForm     = document.getElementById('userForm');
const elActiveSelect = document.getElementById('in_active_role');
const elPassInput    = document.getElementById('in_password');
const elPassHint     = document.getElementById('passHint');
const elToggleIcon   = document.getElementById('toggleIcon');
const elSubmitBtn    = document.getElementById('submitBtn');
const elSubmitText   = document.getElementById('submitText');

// ---------------------------------------------------------------
// Toggle password visibility
// ---------------------------------------------------------------
function togglePassword() {
    const isPass = elPassInput.type === 'password';
    elPassInput.type = isPass ? 'text' : 'password';
    elToggleIcon.classList.toggle('fa-eye',       !isPass);
    elToggleIcon.classList.toggle('fa-eye-slash',  isPass);
}

// ---------------------------------------------------------------
// Sync dropdown "Role Aktif" berdasarkan pilihan Tom Select
// ---------------------------------------------------------------
function syncActiveRole(savedActiveId = null) {
    const selectedIds      = roleSelect.getValue();
    const currentActiveVal = elActiveSelect.value;
    elActiveSelect.innerHTML = '';

    if (selectedIds.length > 0) {
        selectedIds.forEach((roleId, index) => {
            const src = document.querySelector(`#in_roles option[value="${roleId}"]`);
            const opt = document.createElement('option');
            opt.value = roleId;
            opt.text  = src ? src.text : roleId;

            if      (savedActiveId && roleId == savedActiveId)           opt.selected = true;
            else if (!savedActiveId && roleId == currentActiveVal)       opt.selected = true;
            else if (!savedActiveId && !currentActiveVal && index === 0) opt.selected = true;

            elActiveSelect.appendChild(opt);
        });
    } else {
        const placeholder = document.createElement('option');
        placeholder.text  = '— Pilih role terlebih dahulu —';
        placeholder.value = '';
        elActiveSelect.appendChild(placeholder);
    }
}

// ---------------------------------------------------------------
// Validasi client-side
// ---------------------------------------------------------------
function clearErrors() {
    document.querySelectorAll('.form-error').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    document.querySelectorAll('.form-input').forEach(el => el.classList.remove('is-invalid'));
}

function showError(field, msg) {
    const errEl = document.getElementById(`err_${field}`);
    const input = document.getElementById(`in_${field}`);
    if (errEl) { errEl.textContent = msg; errEl.classList.remove('hidden'); }
    if (input)  input.classList.add('is-invalid');
}

function validateForm() {
    clearErrors();
    let valid = true;

    const name     = document.getElementById('in_name').value.trim();
    const username = document.getElementById('in_username').value.trim();
    const password = elPassInput.value;
    const roles    = roleSelect.getValue();
    const isEdit   = document.getElementById('methodField').innerHTML.includes('PUT');

    if (!name)                             { showError('name',     'Nama lengkap wajib diisi.'); valid = false; }
    if (!username)                         { showError('username', 'Username wajib diisi.'); valid = false; }
    if (!isEdit && !password)              { showError('password', 'Password wajib diisi saat tambah user.'); valid = false; }
    if (password && password.length < 6)  { showError('password', 'Password minimal 6 karakter.'); valid = false; }
    if (roles.length === 0)               { showError('roles',    'Pilih minimal satu role.'); valid = false; }

    return valid;
}

// ---------------------------------------------------------------
// Submit dengan loading state
// ---------------------------------------------------------------
elUserForm.addEventListener('submit', function(e) {
    if (!validateForm()) { e.preventDefault(); return; }
    elSubmitBtn.disabled     = true;
    elSubmitText.textContent = 'Menyimpan...';
    elSubmitBtn.querySelector('i').className = 'fas fa-spinner fa-spin text-xs';
});

// ---------------------------------------------------------------
// Modal: Tambah User
// ---------------------------------------------------------------
function openAddModal() {
    clearErrors();
    elUserForm.reset();
    roleSelect.clear();

    document.getElementById('modalTitle').textContent    = 'Tambah User Baru';
    document.getElementById('modalSubtitle').textContent = 'Isi data lengkap untuk membuat akun pegawai baru.';
    document.getElementById('methodField').innerHTML     = '';

    elPassInput.type       = 'password';
    elPassInput.value      = '12345678';
    elPassHint.textContent = '* Default: 12345678. Klik ikon mata untuk melihat.';
    elToggleIcon.className = 'fas fa-eye text-xs';

    elSubmitText.textContent = 'Simpan Data';
    elSubmitBtn.querySelector('i').className = 'fas fa-save text-xs';
    elSubmitBtn.disabled = false;

    elUserForm.action = "{{ route('users.store') }}";
    syncActiveRole();

    elUserModal.classList.remove('hidden');
    setTimeout(() => document.getElementById('in_name').focus(), 100);
}

// ---------------------------------------------------------------
// Modal: Edit User
// ---------------------------------------------------------------
function openEditModal(user, userRoles) {
    clearErrors();
    elUserForm.reset();

    document.getElementById('modalTitle').textContent    = 'Edit Data User';
    document.getElementById('modalSubtitle').textContent = `Mengubah akun: ${user.name}`;
    document.getElementById('methodField').innerHTML     = `<input type="hidden" name="_method" value="PUT">`;

    elPassInput.type       = 'password';
    elPassInput.value      = '';
    elPassHint.textContent = '* Kosongkan jika tidak ingin mengubah password.';
    elToggleIcon.className = 'fas fa-eye text-xs';

    elSubmitText.textContent = 'Perbarui Data';
    elSubmitBtn.querySelector('i').className = 'fas fa-save text-xs';
    elSubmitBtn.disabled = false;

    elUserForm.action = `/admin/users/${user.id}`;

    document.getElementById('in_name').value     = user.name     ?? '';
    document.getElementById('in_nip').value      = user.nip      ?? '';
    document.getElementById('in_username').value = user.username ?? '';

    roleSelect.setValue(userRoles);
    syncActiveRole(user.active_role_id);

    elUserModal.classList.remove('hidden');
    setTimeout(() => document.getElementById('in_name').focus(), 100);
}

// ---------------------------------------------------------------
// Tutup modal user
// ---------------------------------------------------------------
function closeModal() {
    elUserModal.classList.add('hidden');
    clearErrors();
    elUserForm.reset();
    roleSelect.clear();
    syncActiveRole();
    elPassInput.type       = 'password';
    elToggleIcon.className = 'fas fa-eye text-xs';
    elSubmitBtn.disabled   = false;
}

// ---------------------------------------------------------------
// Modal: Konfirmasi Hapus
// ---------------------------------------------------------------
function openDeleteModal(userId, userName) {
    document.getElementById('deleteTargetName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    elDeleteModal.classList.remove('hidden');
}

function closeDeleteModal() {
    elDeleteModal.classList.add('hidden');
}

// ---------------------------------------------------------------
// Search / filter tabel (client-side)
// ---------------------------------------------------------------
function filterTable(q) {
    const rows     = document.querySelectorAll('.user-row');
    const noResult = document.getElementById('noSearchResult');
    const countEl  = document.getElementById('visibleCount');
    const keyword  = q.trim().toLowerCase();
    let visible    = 0;

    rows.forEach(row => {
        const match = !keyword || row.dataset.search.includes(keyword);
        row.classList.toggle('hidden', !match);
        if (match) visible++;
    });

    noResult.classList.toggle('hidden', visible > 0 || !keyword);
    countEl.textContent = keyword ? visible : rows.length;
}

// ---------------------------------------------------------------
// Escape menutup modal aktif
// ---------------------------------------------------------------
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (!elUserModal.classList.contains('hidden'))   closeModal();
    if (!elDeleteModal.classList.contains('hidden')) closeDeleteModal();
});

// ---------------------------------------------------------------
// Auto-dismiss flash notification setelah 5 detik
// ---------------------------------------------------------------
const elFlash = document.getElementById('flashNotif');
if (elFlash) setTimeout(() => elFlash.remove(), 5000);
</script>

@endsection
