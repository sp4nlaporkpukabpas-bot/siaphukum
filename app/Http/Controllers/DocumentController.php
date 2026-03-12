<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('category', 'parent')->orderBy('created_at', 'desc')->get();
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $categories      = Category::all();
        $parentDocuments = Document::whereNull('parent_id')->get();
        return view('admin.documents.create', compact('categories', 'parentDocuments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'document_number' => 'required|unique:documents,document_number',
            'document_date'   => 'required|date',
            'file'            => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $path = $request->file('file')->store('hukum/dokumen', 'public');

        Document::create([
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'parent_id'       => $request->parent_id,
            'document_number' => $request->document_number,
            'document_date'   => $request->document_date,
            'upload_date'     => now(),
            'file_path'       => $path,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function edit(Document $document)
    {
        $categories      = Category::all();
        $parentDocuments = Document::whereNull('parent_id')
            ->where('id', '!=', $document->id)
            ->get();

        return view('admin.documents.edit', compact('document', 'categories', 'parentDocuments'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'document_number' => 'required|unique:documents,document_number,' . $document->id,
            'document_date'   => 'required|date',
            'file'            => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = [
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'parent_id'       => $request->parent_id,
            'document_number' => $request->document_number,
            'document_date'   => $request->document_date,
        ];

        if ($request->hasFile('file')) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $request->file('file')->store('hukum/dokumen', 'public');
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        try {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();

            return back()->with('success', 'Dokumen dan file terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download satu dokumen (cek izin can_download)
     */
    public function download(Document $document)
    {
        $permission = $document->category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        if (!$permission || !$permission->pivot->can_download) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh dokumen ini.');
        }

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Berkas fisik tidak ditemukan di server.');
        }

        $filePath     = storage_path('app/public/' . $document->file_path);
        $extension    = pathinfo($filePath, PATHINFO_EXTENSION);
        $downloadName = Str::slug($document->name) . '.' . $extension;

        return response()->download($filePath, $downloadName);
    }

    /**
     * Batch download: terima array ID, buat ZIP, kirim ke browser.
     * Jika hanya 1 ID dikirim, tetap dibungkus ZIP agar konsisten.
     */
    public function batchDownload(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1|max:50',
            'ids.*' => 'integer|exists:documents,id',
        ]);

        $documents = Document::with('category')->whereIn('id', $request->ids)->get();

        // Cek izin can_download untuk setiap dokumen
        foreach ($documents as $doc) {
            $permission = $doc->category->roles()
                ->where('role_id', auth()->user()->active_role_id)
                ->first();

            if (!$permission || !$permission->pivot->can_download) {
                abort(403, "Akses ditolak untuk dokumen: {$doc->name}");
            }
        }

        // Pastikan direktori temp tersedia
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $zipName = 'siaphukum-' . now()->format('Ymd-His') . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat arsip ZIP. Silakan coba lagi.');
        }

        $addedCount = 0;
        foreach ($documents as $doc) {
            $filePath = storage_path('app/public/' . $doc->file_path);
            if (!file_exists($filePath)) {
                continue; // lewati file yang tidak ditemukan, jangan abort
            }

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            // Nama file di dalam ZIP: slug-nama + id unik agar tidak tabrakan
            $entryName = Str::slug($doc->document_number) . '_' . Str::slug(Str::limit($doc->name, 50)) . '.' . $extension;

            $zip->addFile($filePath, $entryName);
            $addedCount++;
        }

        $zip->close();

        if ($addedCount === 0) {
            @unlink($zipPath);
            return back()->with('error', 'Tidak ada berkas yang dapat diunduh (file tidak ditemukan di server).');
        }

        // Kirim ZIP ke browser, hapus file temp setelah terkirim
        return response()
            ->download($zipPath, $zipName, [
                'Content-Type'        => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipName . '"',
            ])
            ->deleteFileAfterSend(true);
    }

    /**
     * Halaman pratinjau dokumen (iframe secured)
     */
    public function preview($id)
    {
        $document = Document::findOrFail($id);

        $permission = $document->category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        if (!$permission || !$permission->pivot->can_view) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumen ini.');
        }

        return view('admin.documents.preview', compact('document'));
    }

    /**
     * Stream PDF secara langsung ke iframe (header anti-download)
     */
    public function viewSecure($id)
    {
        $doc      = Document::findOrFail($id);
        $filePath = storage_path('app/public/' . $doc->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type'           => 'application/pdf',
            'Content-Disposition'    => 'inline; filename="' . $doc->name . '.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'                 => 'no-cache',
        ]);
    }
}
