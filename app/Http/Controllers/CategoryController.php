<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::with('roles')->get();
        $roles = Role::all();
        return view('admin.categories.index', compact('categories', 'roles'));
    }

    public function show(Category $category)
    {
        $activeRoleId = auth()->user()->active_role_id;

        $permission = $category->roles()
            ->where('role_id', $activeRoleId)
            ->first();

        if (!$permission || (!$permission->pivot->can_view && !$permission->pivot->can_download)) {
            abort(403, 'Anda tidak memiliki hak akses untuk kategori ini.');
        }

        // 1. Ambil dokumen utama dalam kategori ini
        $documents = $category->documents()->orderBy('document_date', 'desc')->get();

        // 2. Ambil Parent unik dari dokumen-dokumen di atas (jika ada)
        // Kita gunakan Eager Loading 'parent' agar tidak query berulang kali (N+1)
        $parentIds = $documents->whereNotNull('parent_id')->pluck('parent_id')->unique();
        $relatedParents = \App\Models\Document::whereIn('id', $parentIds)->get();

        return view('admin.categories.show', compact('category', 'permission', 'documents', 'relatedParents'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        
        $category = Category::create(['name' => $request->name]);

        // Proses Permission Role
        if ($request->has('permissions')) {
            foreach ($request->permissions as $roleId => $perms) {
                $category->roles()->attach($roleId, [
                    'can_view' => isset($perms['can_view']),
                    'can_download' => isset($perms['can_download']),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Kategori dan hak akses berhasil dibuat');
    }

    public function update(Request $request, Category $category) {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update(['name' => $request->name]);

        // Sinkronisasi Permission Role
        $syncData = [];
        if ($request->has('permissions')) {
            foreach ($request->permissions as $roleId => $perms) {
                $syncData[$roleId] = [
                    'can_view' => isset($perms['can_view']),
                    'can_download' => isset($perms['can_download']),
                ];
            }
        }
        $category->roles()->sync($syncData);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori dihapus');
    }
}