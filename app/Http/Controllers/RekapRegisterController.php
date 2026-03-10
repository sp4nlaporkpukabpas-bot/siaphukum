<?php

namespace App\Http\Controllers;

use App\Models\RekapRegister;
use Illuminate\Http\Request;

class RekapRegisterController extends Controller
{
    /**
     * Menampilkan daftar rekap register.
     */
    public function index(Request $request)
    {
        $query = RekapRegister::query();

        if ($request->has('search')) {
            $query->where('nama_rekap', 'like', '%' . $request->search . '%');
        }

        $rekaps = $query->orderBy('tahun', 'desc')->get();
        return view('admin.rekap-register.index', compact('rekaps'));
    }

    /**
     * Menyimpan data rekap baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rekap'   => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer',
            'link_dokumen' => 'required|url',
            'is_visible'   => 'required|boolean',
        ]);

        RekapRegister::create($validated);

        return redirect()->route('rekap-register.index')
            ->with('success', 'Data rekap register berhasil ditambahkan!');
    }

    /**
     * Memperbarui data rekap yang ada.
     */
    public function update(Request $request, string $id)
    {
        $rekap = RekapRegister::findOrFail($id);

        $validated = $request->validate([
            'nama_rekap'   => 'required|string|max:255',
            'tahun'        => 'required|digits:4|integer',
            'link_dokumen' => 'required|url',
            'is_visible'   => 'required', 
        ]);

        // Memastikan is_visible dikonversi menjadi boolean (0 atau 1)
        $validated['is_visible'] = $request->has('is_visible') ? (bool)$request->is_visible : false;

        $rekap->update($validated);

        return redirect()->route('rekap-register.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Menghapus data rekap.
     */
    public function destroy(string $id)
    {
        $rekap = RekapRegister::findOrFail($id);
        $rekap->delete();

        return redirect()->route('rekap-register.index')
            ->with('success', 'Data rekap register berhasil dihapus!');
    }

    /**
     * Method create, show, dan edit tidak diperlukan karena 
     * kita menggunakan sistem Modal dalam satu halaman (SPA-like).
     */
}