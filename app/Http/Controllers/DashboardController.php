<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use App\Models\RekapRegister; // Import Model Baru
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userName = Auth::user()->name;

        // Statistik Utama
        $totalDokumen = Document::count();
        $totalKategori = Category::count();

        // Ambil rekap register yang aktif saja
        $activeRekaps = RekapRegister::where('is_visible', true)
            ->orderBy('tahun', 'desc')
            ->get();

        // 1. Dokumen Terbaru
        $recentDocs = Document::with('category')
            ->orderBy('document_date', 'desc')
            ->take(10)
            ->get();

        // 2. Aktivitas Terbaru
        $recentUploads = Document::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Filter Statistik
        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        $categoriesStats = Category::withCount(['documents' => function ($query) use ($selectedMonth, $selectedYear) {
            $query->whereMonth('document_date', $selectedMonth)
                  ->whereYear('document_date', $selectedYear);
        }])->get();

        return view('dashboard', compact(
            'userName', 'totalDokumen', 'totalKategori', 
            'recentDocs', 'recentUploads', 'categoriesStats',
            'selectedMonth', 'selectedYear', 'activeRekaps'
        ));
    }
}