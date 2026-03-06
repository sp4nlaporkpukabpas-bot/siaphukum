<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userName = Auth::user()->name;

        // Statistik Utama
        $totalDokumen = Document::count();
        $totalKategori = Category::count();

        // 1. Dokumen Terbaru berdasarkan Tanggal Penetapan
        $recentDocs = Document::with('category')
            ->orderBy('document_date', 'desc')
            ->take(10)
            ->get();

        // 2. Aktivitas Terbaru berdasarkan Waktu Upload (created_at)
        $recentUploads = Document::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Filter Bulan & Tahun untuk Statistik Kategori
        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        // Hitung dokumen per kategori berdasarkan filter waktu
        $categoriesStats = Category::withCount(['documents' => function ($query) use ($selectedMonth, $selectedYear) {
            $query->whereMonth('document_date', $selectedMonth)
                  ->whereYear('document_date', $selectedYear);
        }])->get();

        return view('dashboard', compact(
            'userName', 
            'totalDokumen', 
            'totalKategori', 
            'recentDocs', 
            'recentUploads',
            'categoriesStats',
            'selectedMonth',
            'selectedYear'
        ));
    }
}