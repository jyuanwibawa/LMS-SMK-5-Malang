<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

    // Tempatkan rute-rute lain yang butuh login di sini
    Route::get('/dashboard', function () {
        return 'Selamat datang di Dashboard, ' . auth()->user()->name;
    })->name('dashboard');
});
