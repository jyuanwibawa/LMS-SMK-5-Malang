<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Redirect;

class DashboardAdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin beserta data statistik.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalGuru = User::whereHas('role', function ($query) {
            $query->where('name', 'guru');
        })->count();
        $totalSiswa = User::whereHas('role', function ($query) {
            $query->where('name', 'siswa');
        })->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSiswa'
        ));
    }

    /**
     * Menampilkan halaman manajemen pengguna.
     */
    public function showUsers()
    {
        // Ambil semua pengguna beserta relasi 'role'-nya untuk performa yang lebih baik
        $users = User::with('role')->latest()->get();

        // Kirim data users ke view
        return view('admin.users.index', compact('users'));
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        if (!class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return Redirect::back()->with('error', 'Paket Excel belum terpasang. Jalankan: composer require maatwebsite/excel');
        }

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new UsersImport, $request->file('file'));
        } catch (\Throwable $e) {
            return Redirect::back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }

        return Redirect::route('admin.users.index')->with('success', 'Import pengguna berhasil.');
    }
}
