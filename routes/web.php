<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardSiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk pengguna yang belum login (Guest)
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Rute untuk pengguna yang sudah login (Authenticated)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // GRUP RUTE UNTUK ADMIN
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
        // Tambahkan rute admin lainnya di sini...
    });

    // // GRUP RUTE UNTUK GURU
    // Route::prefix('guru')->name('guru.')->group(function () {
    //     Route::get('/dashboard', [DashboardGuruController::class, 'index'])->name('dashboard');
    //     // Tambahkan rute guru lainnya di sini...
    // });

    // // GRUP RUTE UNTUK SISWA
    // Route::prefix('siswa')->name('siswa.')->group(function () {
    //     Route::get('/dashboard', [DashboardSiswaController::class, 'index'])->name('dashboard');
    //     // Tambahkan rute siswa lainnya di sini...
    // });
});
