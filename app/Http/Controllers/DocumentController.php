<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\DocumentAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DocumentController extends Controller
{
    // ----------------------------------------------------------------
    // Helper: parse User-Agent → device / browser / OS
    // ----------------------------------------------------------------
    private function parseUserAgent(string $ua): array
    {
        // --- Device type ---
        $deviceType = 'desktop';
        if (preg_match('/bot|crawl|slurp|spider|mediapartners/i', $ua)) {
            $deviceType = 'bot';
        } elseif (preg_match('/tablet|ipad|kindle|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop/i', $ua)) {
            $deviceType = 'mobile';
        }

        // --- OS ---
        $osName    = null;
        $osVersion = null;
        $osPatterns = [
            '/windows nt 10\.0/i'                     => ['Windows', '10/11'],
            '/windows nt 6\.3/i'                      => ['Windows', '8.1'],
            '/windows nt 6\.2/i'                      => ['Windows', '8'],
            '/windows nt 6\.1/i'                      => ['Windows', '7'],
            '/windows/i'                              => ['Windows', null],
            '/android ([0-9]+(?:\.[0-9]+)*)/i'        => ['Android', null],
            '/cpu iphone os ([0-9_]+)/i'              => ['iOS', null],
            '/ipad.*os ([0-9_]+)/i'                   => ['iPadOS', null],
            '/mac os x ([0-9_]+)/i'                   => ['macOS', null],
            '/linux/i'                                => ['Linux', null],
        ];
        foreach ($osPatterns as $pattern => $info) {
            if (preg_match($pattern, $ua, $m)) {
                $osName    = $info[0];
                $osVersion = $info[1] ?? (isset($m[1]) ? str_replace('_', '.', $m[1]) : null);
                break;
            }
        }

        // --- Browser ---
        // BUG FIX: Safari asli memakai pola "Version/X.X ... Safari/".
        // Chrome/Edge/dll juga mengandung "Safari/" di UA-nya, sehingga
        // deteksi Safari HARUS menggunakan "Version/" — bukan "Safari/" langsung.
        $browserName    = null;
        $browserVersion = null;
        $browserPatterns = [
            '/edg\/([0-9]+(?:\.[0-9]+)*)/i'               => 'Edge',
            '/opr\/([0-9]+(?:\.[0-9]+)*)/i'               => 'Opera',
            '/opera\/([0-9]+(?:\.[0-9]+)*)/i'             => 'Opera',
            '/samsungbrowser\/([0-9]+(?:\.[0-9]+)*)/i'    => 'Samsung Browser',
            '/ucbrowser\/([0-9]+(?:\.[0-9]+)*)/i'         => 'UC Browser',
            '/firefox\/([0-9]+(?:\.[0-9]+)*)/i'           => 'Firefox',
            '/chrome\/([0-9]+(?:\.[0-9]+)*)/i'            => 'Chrome',
            // Safari asli: "Version/X.X" ada sebelum "Safari/" dan TIDAK ada "Chrome/"
            '/version\/([0-9]+(?:\.[0-9]+)*).*safari\//i' => 'Safari',
        ];
        foreach ($browserPatterns as $pattern => $name) {
            if (preg_match($pattern, $ua, $m)) {
                $browserName    = $name;
                $browserVersion = $m[1] ?? null;
                break;
            }
        }
        if (!$browserName && !empty($ua)) {
            $browserName = 'Unknown';
        }

        // --- Device brand & model ---
        $deviceBrand = null;
        $deviceModel = null;
        if (in_array($deviceType, ['mobile', 'tablet'])) {
            // Apple
            if (preg_match('/iphone|ipad|ipod/i', $ua)) {
                $deviceBrand = 'Apple';
                if (preg_match('/ipad/i', $ua))     $deviceModel = 'iPad';
                elseif (preg_match('/ipod/i', $ua)) $deviceModel = 'iPod';
                else                                $deviceModel = 'iPhone';
            }

            // Android brands
            $brandMap = [
                '/samsung/i'     => 'Samsung',
                '/xiaomi|miui/i' => 'Xiaomi',
                '/oppo/i'        => 'OPPO',
                '/vivo/i'        => 'Vivo',
                '/huawei/i'      => 'Huawei',
                '/realme/i'      => 'Realme',
                '/nokia/i'       => 'Nokia',
                '/asus/i'        => 'Asus',
                '/infinix/i'     => 'Infinix',
                '/tecno/i'       => 'Tecno',
            ];
            foreach ($brandMap as $pat => $brand) {
                if (preg_match($pat, $ua)) {
                    $deviceBrand = $brand;
                    break;
                }
            }

            // BUG FIX: model Android — ambil token sebelum "Build/" di dalam kurung UA
            // Contoh: "Linux; Android 14; SM-S918B Build/..." → SM-S918B
            if (!$deviceModel && preg_match('/;\s*([A-Za-z0-9][A-Za-z0-9\s\-]+?)\s+Build\//i', $ua, $m)) {
                $deviceModel = trim($m[1]);
            }
        }

        return compact('deviceType', 'osName', 'osVersion', 'browserName', 'browserVersion', 'deviceBrand', 'deviceModel');
    }

    // ----------------------------------------------------------------
    // Helper: GeoIP lookup via ip-api.com (HTTP, gratis, tanpa API key)
    //
    // CATATAN: ip-api.com endpoint HTTPS memerlukan paket Pro berbayar.
    // Endpoint HTTP gratis tetap valid selama diakses dari server (bukan browser).
    // Jika server production memblokir outbound HTTP, opsi alternatif:
    //   1. Pasang MaxMind GeoLite2 (offline): composer require geoip2/geoip2
    //   2. Gunakan layanan GeoIP lain yang support HTTPS gratis (misal: ipinfo.io)
    // ----------------------------------------------------------------
    private function lookupGeo(string $ip): array
    {
        $blank = [
            'country_code' => null,
            'country_name' => null,
            'region_name'  => null,
            'city_name'    => null,
            'latitude'     => null,
            'longitude'    => null,
        ];

        // Skip IP lokal / private / reserved
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $blank;
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,countryCode,country,regionName,city,lat,lon',
                'lang'   => 'id',
            ]);

            if ($response->successful()) {
                $geo = $response->json();
                if (($geo['status'] ?? '') === 'success') {
                    return [
                        'country_code' => $geo['countryCode'] ?? null,
                        'country_name' => $geo['country']     ?? null,
                        'region_name'  => $geo['regionName']  ?? null,
                        'city_name'    => $geo['city']        ?? null,
                        'latitude'     => $geo['lat']         ?? null,
                        'longitude'    => $geo['lon']         ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[DocumentLog] GeoIP lookup gagal', [
                'ip'    => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        return $blank;
    }

    // ----------------------------------------------------------------
    // Helper utama: catat log akses lengkap
    // ----------------------------------------------------------------
    private function logAccess(Document $document, string $action): void
    {
        $request = request();
        $ua      = (string) ($request->userAgent() ?? '');
        $ip      = (string) ($request->ip()        ?? '');

        $parsed = $this->parseUserAgent($ua);
        $geo    = $this->lookupGeo($ip);

        DocumentAccessLog::create([
            'document_id'     => $document->id,
            'user_id'         => auth()->id(),
            'action'          => $action,

            'ip_address'      => $ip ?: null,
            'user_agent'      => $ua ? Str::limit($ua, 500) : null,
            'browser_name'    => $parsed['browserName'],
            'browser_version' => $parsed['browserVersion'],
            'os_name'         => $parsed['osName'],
            'os_version'      => $parsed['osVersion'],
            'device_type'     => $parsed['deviceType'],
            'device_brand'    => $parsed['deviceBrand'],
            'device_model'    => $parsed['deviceModel'],

            'country_code'    => $geo['country_code'],
            'country_name'    => $geo['country_name'],
            'region_name'     => $geo['region_name'],
            'city_name'       => $geo['city_name'],
            'latitude'        => $geo['latitude'],
            'longitude'       => $geo['longitude'],
        ]);
    }

    // ----------------------------------------------------------------
    // CRUD biasa
    // ----------------------------------------------------------------

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

    // ----------------------------------------------------------------
    // Download & Preview
    // ----------------------------------------------------------------

    public function download(Document $document)
    {
        $permission = $document->category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        $canDownload = $permission && $permission->pivot->can_download;

        // ✅ Catat log SEBELUM cek izin — agar akses ditolak pun tetap terekam
        $this->logAccess($document, $canDownload ? 'download' : 'download_denied');

        if (!$canDownload) {
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

    public function batchDownload(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1|max:50',
            'ids.*' => 'integer|exists:documents,id',
        ]);

        $documents = Document::with('category')->whereIn('id', $request->ids)->get();

        // ✅ Catat log SEBELUM cek izin — satu per dokumen, termasuk yang ditolak
        foreach ($documents as $doc) {
            $permission  = $doc->category->roles()
                ->where('role_id', auth()->user()->active_role_id)
                ->first();
            $canDownload = $permission && $permission->pivot->can_download;

            $this->logAccess($doc, $canDownload ? 'batch_download' : 'batch_download_denied');

            if (!$canDownload) {
                abort(403, "Akses ditolak untuk dokumen: {$doc->name}");
            }
        }

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
            if (!file_exists($filePath)) continue;

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $entryName = Str::slug($doc->document_number) . '_' . Str::slug(Str::limit($doc->name, 50)) . '.' . $extension;
            $zip->addFile($filePath, $entryName);
            $addedCount++;
        }

        $zip->close();

        if ($addedCount === 0) {
            @unlink($zipPath);
            return back()->with('error', 'Tidak ada berkas yang dapat diunduh (file tidak ditemukan di server).');
        }

        return response()
            ->download($zipPath, $zipName, [
                'Content-Type'        => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipName . '"',
            ])
            ->deleteFileAfterSend(true);
    }

    public function preview($id)
    {
        $document = Document::findOrFail($id);

        $permission = $document->category->roles()
            ->where('role_id', auth()->user()->active_role_id)
            ->first();

        if (!$permission || !$permission->pivot->can_view) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumen ini.');
        }

        $this->logAccess($document, 'preview');

        return view('admin.documents.preview', compact('document'));
    }

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
