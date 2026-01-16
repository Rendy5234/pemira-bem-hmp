<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventController;

// Redirect root ke admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// ==================== ADMIN ROUTES ====================

// Admin Login (Guest only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    // Admin Protected Routes (Must be logged in)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Event Routes
        Route::resource('event', EventController::class)->except(['show'])->names([
            'index' => 'event.index',
            'create' => 'event.create',
            'store' => 'event.store',
            'edit' => 'event.edit',
            'update' => 'event.update',
            'destroy' => 'event.destroy',
        ]);
        Route::get('event/{id}/detail', [EventController::class, 'show'])->name('event.show');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Event Routes
        Route::prefix('event')->name('event.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            Route::get('/{id}/detail', [EventController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('update');
            Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');

            // Trash
            Route::get('/trash', [EventController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [EventController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [EventController::class, 'forceDelete'])->name('forceDelete');
        });
    });
});
