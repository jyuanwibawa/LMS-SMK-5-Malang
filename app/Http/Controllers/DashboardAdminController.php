<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
