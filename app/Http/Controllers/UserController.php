<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() {
        // Menggunakan eager loading untuk performa maksimal
        $users = User::with(['roles', 'activeRole'])->latest()->get();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|unique:users,nip',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'roles' => 'required|array|min:1',
            'active_role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'nip' => $data['nip'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'active_role_id' => $data['active_role_id'],
        ]);

        // Sinkronisasi many-to-many
        $user->roles()->sync($request->roles);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['nullable', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'roles' => 'required|array|min:1',
            'active_role_id' => 'required|exists:roles,id'
        ]);

        $user->update([
            'name' => $data['name'],
            'nip' => $data['nip'],
            'username' => $data['username'],
            'active_role_id' => $data['active_role_id'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->roles()->sync($request->roles);

        return redirect()->back()->with('success', 'Data user diperbarui.');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->back()->with('success', 'User telah dihapus.');
    }
}