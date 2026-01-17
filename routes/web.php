<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
// PERBAIKAN 1: Gunakan Controller dari folder User sesuai Module 13
use App\Http\Controllers\User\EventController as UserEventController; 
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\HistoriesController;
use App\Http\Controllers\User\OrderController;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/
// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Detail Event
// PERBAIKAN 2: Panggil UserEventController yang sudah kita buat aliasnya di atas
Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| Admin Routes (Hanya Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // 1. Dashboard Admin
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // 2. CRUD Kategori
        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);
        
        // 3. CRUD Event
        Route::resource('events', AdminEventController::class);

        // 4. CRUD Tiket
        Route::resource('tickets', TiketController::class)->only(['store', 'update', 'destroy']);
        
        // 5. History Pembelian
        Route::get('/histories', [HistoriesController::class, 'index'])->name('histories.index');
        Route::get('/histories/{id}', [HistoriesController::class, 'show'])->name('histories.show');
    });

/*
|--------------------------------------------------------------------------
| User Routes (User Login Biasa)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    //Route ini mengarahkan request ke OrderController pada method store.
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';