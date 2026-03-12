<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ── DASHBOARD ────────────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── PROFIL & AKUN ────────────────────────────────────────────────────────
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/switch-role', [ProfileController::class, 'switchRole'])->name('role.switch');

    // ── AKSES DOKUMEN (Sidebar Kategori) ────────────────────────────────────
    Route::get('/kategori/{id}/lihat', [CategoryController::class, 'show'])->name('categories.show');

    // PENTING: Route eksplisit dokumen harus didaftarkan SEBELUM resource
    // agar tidak tertimpa oleh pola {document} milik resource.
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
         ->name('documents.download');

    // Batch download: POST karena mengirim array ID via form/fetch
    Route::post('/documents/batch-download', [DocumentController::class, 'batchDownload'])
         ->name('documents.batch-download');

    // Preview & Secure View (akses langsung, di luar prefix master)
    Route::get('/documents/preview/{id}', [DocumentController::class, 'preview'])
         ->name('documents.preview');
    Route::get('/documents/view-secure/{id}', [DocumentController::class, 'viewSecure'])
         ->name('documents.view-secure');

    // ── DATA MASTER ──────────────────────────────────────────────────────────
    Route::prefix('master')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('documents', DocumentController::class);
        Route::resource('rekap-register', RekapRegisterController::class);
    });

    // ── ADMINISTRASI ─────────────────────────────────────────────────────────
    Route::prefix('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    });

    // ── LOGOUT ───────────────────────────────────────────────────────────────
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
