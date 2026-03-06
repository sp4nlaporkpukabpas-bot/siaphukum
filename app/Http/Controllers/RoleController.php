<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role; // Pastikan model Role sudah ada

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        $role->save();

        return redirect()->back()->with('success', 'Nama tampilan role berhasil diperbarui!');
    }
    
    // Kita tidak membuat fungsi destroy() agar tidak ada celah penghapusan lewat URL
}