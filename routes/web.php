<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardSiswaController;
use App\Http\Controllers\Admin\AcademicClassController;
use App\Http\Controllers\Admin\CourseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute Halaman Utama, langsung arahkan ke halaman login
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
        Route::get('/users', [DashboardAdminController::class, 'showUsers'])->name('users.index');
        Route::post('/users', [DashboardAdminController::class, 'storeUser'])->name('users.store');
        Route::post('/users/import', [DashboardAdminController::class, 'importUsers'])->name('users.import');
        Route::put('/users/{user}', [DashboardAdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [DashboardAdminController::class, 'destroyUser'])->name('users.destroy');
        // Halaman index Manajemen Akademik di /admin/akademik
        Route::get('/akademik', [DashboardAdminController::class, 'academic'])->name('academic.index');
        Route::get('/akademik/kelas/{class}', [DashboardAdminController::class, 'manageClass'])->name('academic.classes.show');
        Route::get('/akademik/mapel/{course}', [DashboardAdminController::class, 'manageCourse'])->name('academic.courses.show');

        // CRUD Kelas
        Route::post('/classes', [AcademicClassController::class, 'store'])->name('classes.store');
        Route::post('/classes/{class}/enrollments', [AcademicClassController::class, 'storeEnrollment'])->name('classes.enrollments.store');
        Route::put('/classes/{class}', [AcademicClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{class}', [AcademicClassController::class, 'destroy'])->name('classes.destroy');
        Route::delete('/classes/{class}/enrollments/{enrollment}', [AcademicClassController::class, 'destroyEnrollment'])->name('classes.enrollments.destroy');

        // CRUD Mata Pelajaran
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        // Teachings for a Course
        Route::post('/courses/{course}/teachings', [CourseController::class, 'storeTeaching'])->name('courses.teachings.store');
        Route::delete('/courses/{course}/teachings/{teaching}', [CourseController::class, 'destroyTeaching'])->name('courses.teachings.destroy');
        Route::get('/logs', [DashboardAdminController::class, 'logs'])->name('logs.index');
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
