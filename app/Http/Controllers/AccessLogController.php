<?php

namespace App\Http\Controllers;

use App\Models\DocumentAccessLog;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentAccessLog::with('document', 'user')->latest();

        // Filter aksi — termasuk _denied
        if ($request->filled('action') && in_array($request->action, DocumentAccessLog::ACTIONS)) {
            $query->where('action', $request->action);
        }

        // Filter user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter dokumen
        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }

        // Filter tipe perangkat
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        // Filter rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs      = $query->paginate(50)->withQueryString();
        $users     = User::orderBy('name')->get();
        $documents = Document::orderBy('name')->get();

        return view('admin.logs.index', compact('logs', 'users', 'documents'));
    }
}
