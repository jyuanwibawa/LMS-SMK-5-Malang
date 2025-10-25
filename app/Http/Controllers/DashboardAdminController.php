<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Redirect;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Teaching;

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
    public function showUsers(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $usersQuery = User::with('role');
        if ($q !== '') {
            $usersQuery->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('identity_number', 'like', "%$q%")
                    ->orWhereHas('role', function ($qr) use ($q) {
                        $qr->where('name', 'like', "%$q%");
                    });
            });
        }
        $users = $usersQuery->latest()->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles', 'q'));
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

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nisn_nuptk' => ['nullable', 'string', 'max:255', 'unique:users,identity_number'],
            'jenis_kelamin' => ['nullable', 'in:Laki-Laki,Perempuan'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['required', 'min:6'],
            'konfirmasi_password' => ['required'],
        ]);

        if ($request->input('password') !== $request->input('konfirmasi_password')) {
            return Redirect::back()->withInput()->withErrors(['konfirmasi_password' => 'Konfirmasi password tidak cocok.']);
        }

        $role = Role::where('name', $request->input('role'))->first();

        User::create([
            'name' => $request->input('nama'),
            'email' => strtolower($request->input('email')),
            'identity_number' => $request->input('nisn_nuptk') ?: null,
            'jenis_kelamin' => $request->input('jenis_kelamin') ?: null,
            'password' => $request->input('password'),
            'role_id' => $role?->id,
        ]);

        return Redirect::route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nisn_nuptk' => ['nullable', 'string', 'max:255', 'unique:users,identity_number,' . $user->id],
            'jenis_kelamin' => ['nullable', 'in:Laki-Laki,Perempuan'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['nullable', 'min:6'],
        ]);

        $role = Role::where('name', $request->input('role'))->first();

        $data = [
            'name' => $request->input('nama'),
            'email' => strtolower($request->input('email')),
            'identity_number' => $request->input('nisn_nuptk') ?: null,
            'jenis_kelamin' => $request->input('jenis_kelamin') ?: null,
            'role_id' => $role?->id,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $user->update($data);

        return Redirect::route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return Redirect::route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function academic()
    {
        $classes = SchoolClass::withCount(['enrollments as students_count'])->orderBy('name')->get();

        $courses = Course::with('teachings')->orderBy('name')->get();

        return view('admin.academic.index', [
            'classes' => $classes,
            'courses' => $courses,
        ]);
    }

    public function logs()
    {
        return view('admin.logs.index');
    }

    public function manageClass(SchoolClass $class)
    {
        $class->load([
            'enrollments.user',
            'teachings.user',
            'teachings.course',
        ]);

        $studentsCount = $class->enrollments->count();
        $teachersCount = $class->teachings->count();

        // Ambil siswa yang belum terdaftar di kelas
        $enrolledUserIds = $class->enrollments->pluck('user_id');
        $availableStudents = \App\Models\User::whereHas('role', function($q){ $q->where('name','siswa'); })
            ->whereNotIn('id', $enrolledUserIds)
            ->orderBy('name')
            ->get(['id','name','identity_number','email']);

        return view('admin.academic.kelolakelas', [
            'class' => $class,
            'studentsCount' => $studentsCount,
            'teachersCount' => $teachersCount,
            'availableStudents' => $availableStudents,
        ]);
    }
}
