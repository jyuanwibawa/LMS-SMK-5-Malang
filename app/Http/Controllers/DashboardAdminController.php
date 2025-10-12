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
        // Menghitung total pengguna
        $totalUsers = User::count();

        // Menghitung total guru
        $totalGuru = User::whereHas('role', function ($query) {
            $query->where('name', 'guru');
        })->count();

        // Menghitung total siswa
        $totalSiswa = User::whereHas('role', function ($query) {
            $query->where('name', 'siswa');
        })->count();

        // Anda bisa menambahkan data lain di sini, contoh:
        // $totalKelas = Kelas::count();

        // Mengirim semua data ke view menggunakan compact()
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSiswa'
            // 'totalKelas'
        ));
    }
}
