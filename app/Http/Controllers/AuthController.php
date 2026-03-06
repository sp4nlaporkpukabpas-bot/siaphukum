<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Tentukan apakah input itu NIP atau Username
        $fieldType = is_numeric($request->nip) ? 'nip' : 'username';

        // 1. Cari User terlebih dahulu
        $user = User::where($fieldType, $request->nip)->first();

        if (!$user) {
            return back()->with('error', 'Akun dengan NIP/Username tersebut tidak terdaftar di sistem.');
        }

        // 2. Cek Password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Kata sandi yang Anda masukkan salah. Silakan coba lagi.')->withInput();
        }

        // 3. Proses Login
        if (Auth::attempt([$fieldType => $request->nip, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->with('error', 'Terjadi kesalahan sistem saat proses login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}