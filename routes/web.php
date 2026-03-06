<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
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
    
    // 1. DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::post('/switch-role', [ProfileController::class, 'switchRole'])->name('role.switch')->middleware('auth');

    // 2. KATEGORI DOKUMEN (Menu Dinamis di Sidebar)
    // Menampilkan isi dokumen berdasarkan kategori tertentu
    Route::get('/kategori/{id}/lihat', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
         ->name('documents.download');
    // Cari di bagian Protected Routes
Route::get('/documents/preview/{id}', [DocumentController::class, 'preview'])->name('documents.preview');
Route::get('/documents/view-secure/{id}', [DocumentController::class, 'viewSecure'])->name('documents.view-secure');

    // 3. MANAJEMEN DATA (Data Master)
    Route::prefix('master')->group(function () {
        // Kelola Kategori (CRUD)
        Route::resource('categories', CategoryController::class);
        
        // Semua Dokumen (CRUD)
        Route::resource('documents', DocumentController::class);
    });

    // 4. ADMINISTRASI (User & Role)
    Route::prefix('admin')->group(function () {
        // Manajemen User
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        
        // Manajemen Role (Hak Akses)
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});