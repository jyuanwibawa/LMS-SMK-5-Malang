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
use App\Models\ActivityLog;

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

        // Kelas aktif: jumlah teaching yang terdaftar (kombinasi guru-mapel-kelas)
        $activeClasses = Teaching::count();

        // Pengguna baru bulan ini
        $newUsersThisMonth = User::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ])->count();

        // ===== Data untuk grafik =====
        // 1. Aktivitas mingguan (menggunakan ActivityLog 7 hari terakhir)
        $startWeek = now()->copy()->subDays(6)->startOfDay();
        $endWeek = now()->copy()->endOfDay();

        $logs = ActivityLog::with('user.role')
            ->whereBetween('timestamp', [$startWeek, $endWeek])
            ->get();

        $dayLabels = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
        $weeklyGuruCounts = [];
        $weeklySiswaCounts = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startWeek->copy()->addDays($i)->format('Y-m-d');
            $logsForDay = $logs->filter(function($log) use ($date) {
                return optional($log->timestamp)->format('Y-m-d') === $date;
            });
            $weeklyGuruCounts[$i] = $logsForDay->filter(function($log){
                return optional(optional($log->user)->role)->name === 'guru';
            })->count();
            $weeklySiswaCounts[$i] = $logsForDay->filter(function($log){
                return optional(optional($log->user)->role)->name === 'siswa';
            })->count();
        }

        // Ambil nilai maksimum dari semua titik (guru+siswa), minimal 1 agar tidak bagi 0
        $maxWeekly = max(array_merge($weeklyGuruCounts, $weeklySiswaCounts, [1]));
        $xMin = 40; $xMax = 270; $yMin = 30; $yMax = 190; $height = $yMax - $yMin; $step = ($xMax - $xMin) / 6;
        $weeklyGuruPoints = [];
        $weeklySiswaPoints = [];
        for ($i = 0; $i < 7; $i++) {
            $x = $xMin + $i * $step;
            $yGuru = $yMax - ($weeklyGuruCounts[$i] / $maxWeekly) * $height;
            $ySiswa = $yMax - ($weeklySiswaCounts[$i] / $maxWeekly) * $height;
            $weeklyGuruPoints[] = round($x,1).','.round($yGuru,1);
            $weeklySiswaPoints[] = round($x,1).','.round($ySiswa,1);
        }
        $weeklyGuruPointsStr = implode(' ', $weeklyGuruPoints);
        $weeklySiswaPointsStr = implode(' ', $weeklySiswaPoints);

        // 2. Registrasi bulanan (6 bulan terakhir)
        $monthLabels = [];
        $monthlyGuruCounts = [];
        $monthlySiswaCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $monthLabels[] = $monthStart->format('M');

            $monthlyGuruCounts[] = User::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereHas('role', function($q){ $q->where('name','guru'); })
                ->count();

            $monthlySiswaCounts[] = User::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereHas('role', function($q){ $q->where('name','siswa'); })
                ->count();
        }

        // Maksimum registrasi bulanan untuk skala grafik, minimal 1
        $maxMonthly = max(array_merge($monthlyGuruCounts, $monthlySiswaCounts, [1]));

        // Aktivitas terbaru untuk panel "Aktivitas Terkini"
        $recentActivities = ActivityLog::with('user.role')
            ->orderByDesc('timestamp')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSiswa',
            'activeClasses',
            'newUsersThisMonth',
            'dayLabels',
            'weeklyGuruCounts',
            'weeklySiswaCounts',
            'weeklyGuruPointsStr',
            'weeklySiswaPointsStr',
            'monthLabels',
            'monthlyGuruCounts',
            'monthlySiswaCounts',
            'maxWeekly',
            'maxMonthly',
            'recentActivities'
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
        $logs = ActivityLog::with('user')
            ->orderByDesc('timestamp')
            ->limit(200)
            ->get();

        return view('admin.logs.index', compact('logs'));
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

    public function manageCourse(\App\Models\Course $course)
    {
        $course->load(['teachings.user', 'teachings.schoolClass']);

        // Guru yang tersedia (role guru)
        $availableTeachers = \App\Models\User::whereHas('role', function($q){ $q->where('name','guru'); })
            ->orderBy('name')
            ->get(['id','name','email']);

        // Semua kelas aktif untuk penugasan
        $classes = \App\Models\SchoolClass::orderBy('level')
            ->orderBy('major')
            ->orderBy('name')
            ->get(['id','name','academic_year']);

        return view('admin.academic.kelolamapel', [
            'course' => $course,
            'availableTeachers' => $availableTeachers,
            'classes' => $classes,
        ]);
    }
}
