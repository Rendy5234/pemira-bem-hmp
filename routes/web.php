<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\KandidatController;
use App\Http\Controllers\Admin\RiwayatPemilihanController;

// Redirect root ke admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// ==================== ADMIN ROUTES ====================

Route::prefix('admin')->name('admin.')->group(function () {

    // ========== GUEST ONLY (Belum Login) ==========
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    // ========== AUTHENTICATED ONLY (Sudah Login) ==========
    Route::middleware('auth:admin')->group(function () {

        // Dashboard & Logout
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // ========== EVENT ROUTES ==========
        Route::prefix('event')->name('event.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            Route::get('/{id}/detail', [EventController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('update');
            Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');

            // Event Trash
            Route::get('/trash', [EventController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [EventController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [EventController::class, 'forceDelete'])->name('forceDelete');

            // Kategori Trash
            Route::get('/trash-kategori', [EventController::class, 'trashKategori'])->name('trashKategori');
            Route::post('/kategori/{id}/restore', [EventController::class, 'restoreKategori'])->name('restoreKategori');
            Route::delete('/kategori/{id}/force-delete', [EventController::class, 'forceDeleteKategori'])->name('forceDeleteKategori');
        });

        // ========== KANDIDAT ROUTES ==========
        Route::prefix('kandidat')->name('kandidat.')->group(function () {
            // Level 1: Pilih Event
            Route::get('/', [KandidatController::class, 'selectEvent'])->name('selectEvent');

            // Level 2: Pilih Kategori
            Route::get('/event/{eventId}', [KandidatController::class, 'selectKategori'])->name('selectKategori');

            // Level 3: CRUD Kandidat
            Route::get('/event/{eventId}/kategori/{kategoriId}', [KandidatController::class, 'index'])->name('index');
            Route::get('/event/{eventId}/kategori/{kategoriId}/create', [KandidatController::class, 'create'])->name('create');
            Route::post('/event/{eventId}/kategori/{kategoriId}', [KandidatController::class, 'store'])->name('store');
            Route::get('/event/{eventId}/kategori/{kategoriId}/{id}', [KandidatController::class, 'show'])->name('show');
            Route::get('/event/{eventId}/kategori/{kategoriId}/{id}/edit', [KandidatController::class, 'edit'])->name('edit');
            Route::put('/event/{eventId}/kategori/{kategoriId}/{id}', [KandidatController::class, 'update'])->name('update');
            Route::delete('/event/{eventId}/kategori/{kategoriId}/{id}', [KandidatController::class, 'destroy'])->name('destroy');

            // Kandidat Trash
            Route::get('/trash', [KandidatController::class, 'trash'])->name('trash');
            Route::post('/trash/{id}/restore', [KandidatController::class, 'restore'])->name('restore');
            Route::delete('/trash/{id}/force-delete', [KandidatController::class, 'forceDelete'])->name('forceDelete');
        });

        // ========== RIWAYAT PEMILIHAN ROUTES ==========
        Route::prefix('riwayat-pemilihan')->name('riwayat-pemilihan.')->group(function () {
            // Level 1: Pilih Event
            Route::get('/', [RiwayatPemilihanController::class, 'selectEvent'])->name('selectEvent');

            // Level 2: Pilih Kategori
            Route::get('/event/{eventId}', [RiwayatPemilihanController::class, 'selectKategori'])->name('selectKategori');

            // Level 3: Lihat Riwayat Pemilihan
            Route::get('/event/{eventId}/kategori/{kategoriId}', [RiwayatPemilihanController::class, 'index'])->name('index');
            Route::get('/event/{eventId}/kategori/{kategoriId}/{id}', [RiwayatPemilihanController::class, 'show'])->name('show');
            Route::delete('/event/{eventId}/kategori/{kategoriId}/{id}', [RiwayatPemilihanController::class, 'destroy'])->name('destroy');

            // Trash
            Route::get('/event/{eventId}/kategori/{kategoriId}/trash', [RiwayatPemilihanController::class, 'trash'])->name('trash');
            Route::post('/event/{eventId}/kategori/{kategoriId}/trash/{id}/restore', [RiwayatPemilihanController::class, 'restore'])->name('restore');
            Route::delete('/event/{eventId}/kategori/{kategoriId}/trash/{id}/force-delete', [RiwayatPemilihanController::class, 'forceDelete'])->name('forceDelete');
        });
    });
});
