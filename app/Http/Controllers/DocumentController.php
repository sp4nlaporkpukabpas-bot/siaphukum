<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        // Eager load category untuk performa, urutkan berdasarkan terbaru
        $documents = Document::with('category', 'parent')->orderBy('created_at', 'desc')->get();
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $categories = Category::all();
        $parentDocuments = Document::whereNull('parent_id')->get();
        return view('admin.documents.create', compact('categories', 'parentDocuments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'document_number' => 'required|unique:documents,document_number',
            'document_date' => 'required|date',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
        ]);

        // Simpan file ke direktori storage/app/public/hukum/dokumen
        $path = $request->file('file')->store('hukum/dokumen', 'public');

        Document::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'parent_id' => $request->parent_id,
            'document_number' => $request->document_number,
            'document_date' => $request->document_date,
            'upload_date' => now(),
            'file_path' => $path,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Logika Hapus: Menghapus record di DB sekaligus file fisiknya
     */
    public function destroy(Document $document)
    {
        try {
            // 1. Cek dan Hapus file fisik dari direktori public storage
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // 2. Hapus record dari database
            $document->delete();

            return back()->with('success', 'Dokumen dan file terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Logika Update: Mengganti file lama dengan yang baru jika ada upload ulang
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'document_number' => 'required|unique:documents,document_number,' . $document->id,
            'document_date' => 'required|date',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'parent_id' => $request->parent_id,
            'document_number' => $request->document_number,
            'document_date' => $request->document_date,
        ];

        // Cek jika user mengunggah file baru
        if ($request->hasFile('file')) {
            // 1. Hapus file lama dari storage agar tidak menjadi sampah/junk file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // 2. Simpan file baru dan ambil path-nya
            $data['file_path'] = $request->file('file')->store('hukum/dokumen', 'public');
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
    }

    public function download(Document $document)
    {
        $category = $document->category;
        
        // Proteksi Hak Akses
        $permission = $category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        if (!$permission || !$permission->pivot->can_download) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh dokumen ini.');
        }

        // Cek Keberadaan Berkas
        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Maaf, berkas fisik tidak ditemukan di server.');
        }

        // Eksekusi Download dengan nama yang rapi (Slug)
        $filePath = storage_path('app/public/' . $document->file_path);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $downloadName = Str::slug($document->name) . '.' . $extension;

        return response()->download($filePath, $downloadName);
    }

    public function edit(Document $document)
    {
        $categories = Category::all();
        $parentDocuments = Document::whereNull('parent_id')
            ->where('id', '!=', $document->id)
            ->get();

        return view('admin.documents.edit', compact('document', 'categories', 'parentDocuments'));
    }

    // Tambahkan fungsi preview
    public function preview($id)
    {
        $document = Document::findOrFail($id);
        
        // Proteksi Hak Akses (Opsional tapi disarankan)
        $category = $document->category;
        $permission = $category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        if (!$permission || !$permission->pivot->can_view) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumen ini.');
        }

        return view('admin.documents.preview', compact('document'));
    }

    // Perbaiki fungsi viewSecure
    public function viewSecure($id)
    {
        $doc = Document::findOrFail($id);
        $filePath = storage_path('app/public/' . $doc->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        // Menggunakan StreamedResponse dengan header anti-download
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $doc->name . '.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}