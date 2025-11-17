<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Teaching;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Announcement;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Total kelas yang diikuti siswa
        $totalClasses = Enrollment::where('user_id', $userId)->count();

        // Ambil teaching_id yang relevan dari kelas yang diikuti
        $classIds = Enrollment::where('user_id', $userId)->pluck('class_id');
        $teachingIds = Teaching::whereIn('class_id', $classIds)->pluck('id');

        // Tugas aktif (deadline >= now)
        $activeTasks = Assignment::whereIn('teaching_id', $teachingIds)
            ->where(function($q){
                $q->whereNull('end_time')->orWhere('end_time', '>=', now());
            })
            ->count();

        // Tugas selesai (jumlah submission)
        $tasksDone = Submission::where('user_id', $userId)->count();

        // Rata-rata nilai dari submission yang sudah dinilai
        $avgGrade = Submission::where('user_id', $userId)
            ->whereNotNull('grade')
            ->avg('grade');
        $avgGrade = is_null($avgGrade) ? null : round($avgGrade);

        // Tugas & Kuis Mendatang (maks 5 terdekat)
        $upcomingWorks = Assignment::with(['teaching.course','teaching.schoolClass'])
            ->whereIn('teaching_id', $teachingIds)
            ->whereNotNull('end_time')
            ->where('end_time', '>=', now())
            ->whereDoesntHave('submissions', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('end_time', 'asc')
            ->limit(5)
            ->get();

        // Pengumuman Terbaru (maks 3 dari kelas siswa)
        $latestAnnouncements = Announcement::with('schoolClass')
            ->whereIn('class_id', $classIds)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        // Progres Bulan Ini
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $monthlyQuery = Assignment::whereIn('teaching_id', $teachingIds)
            ->whereNotNull('end_time')
            ->whereBetween('end_time', [$start, $end]);
        $monthlyTotal = (clone $monthlyQuery)->count();
        $monthlyDone = (clone $monthlyQuery)
            ->whereHas('submissions', function($q) use ($userId){ $q->where('user_id', $userId); })
            ->count();
        $monthlyRemaining = max($monthlyTotal - $monthlyDone, 0);
        $monthlyPercent = $monthlyTotal > 0 ? round(($monthlyDone / $monthlyTotal) * 100) : 0;

        return view('siswa.dashboard', compact(
            'totalClasses','activeTasks','tasksDone','avgGrade',
            'upcomingWorks','latestAnnouncements',
            'monthlyTotal','monthlyDone','monthlyRemaining','monthlyPercent'
        ));
    }
}
