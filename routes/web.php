<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', function () {
    return redirect()->route('complaints.index');
});

// Routes yang wajib login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // User & Admin bisa akses
    Route::resource('complaints', ComplaintController::class);

    // Profile management (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Admin bisa akses semua complaint untuk dikelola
    Route::get('/complaints', [ComplaintController::class, 'adminIndex'])->name('complaints.index');
    Route::resource('complaints', ComplaintController::class)->except(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';