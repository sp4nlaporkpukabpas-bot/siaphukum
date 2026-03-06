<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function switchRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = Auth::user();

        // Keamanan: Cek apakah user memang memiliki role tersebut di tabel pivot role_user
        $hasRole = $user->roles()->where('role_id', $request->role_id)->exists();

        if ($hasRole) {
            $user->update([
                'active_role_id' => $request->role_id
            ]);

            return back()->with('success', 'Role berhasil diubah ke ' . $user->activeRole->display_name);
        }

        return back()->with('error', 'Anda tidak memiliki akses ke role tersebut.');
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama Anda salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}