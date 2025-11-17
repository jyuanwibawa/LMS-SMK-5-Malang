<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\Teaching;
use Illuminate\Http\Request;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Semua kelas yang diajar guru ini
        $teachings = Teaching::with(['schoolClass', 'course'])
            ->where('user_id', $user->id)
            ->get();

        $teachingIds = $teachings->pluck('id');
        $classIds = $teachings->pluck('class_id')->filter()->unique();

        // Statistik utama
        $totalClasses = $teachings->count();
        $totalStudents = $classIds->isEmpty()
            ? 0
            : Enrollment::whereIn('class_id', $classIds)
                ->distinct('user_id')
                ->count('user_id');

        $now = now();

        $activeTasks = Assignment::whereIn('teaching_id', $teachingIds)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_time')->orWhere('end_time', '>', $now);
            })
            ->count();

        $assignmentIds = Assignment::whereIn('teaching_id', $teachingIds)->pluck('id');

        $pendingGrading = $assignmentIds->isEmpty()
            ? 0
            : Submission::whereIn('assignment_id', $assignmentIds)
                ->whereNotNull('submitted_at')
                ->whereNull('grade')
                ->count();

        // Deadline terdekat (tugas & kuis)
        $upcomingDeadlines = Assignment::with(['teaching.course', 'teaching.schoolClass'])
            ->whereIn('teaching_id', $teachingIds)
            ->whereNotNull('end_time')
            ->where('end_time', '>', $now)
            ->orderBy('end_time')
            ->limit(5)
            ->get();

        // Pengumpulan terbaru: progress per tugas (beberapa tugas terakhir yang aktif)
        $latestProgress = collect();
        if ($assignmentIds->isNotEmpty()) {
            $recentSubmissions = Submission::with(['assignment.teaching.course', 'assignment.teaching.schoolClass'])
                ->whereIn('assignment_id', $assignmentIds)
                ->orderByDesc('submitted_at')
                ->limit(50)
                ->get();

            // Jumlah siswa per kelas untuk semua kelas guru
            $enrollCounts = Enrollment::whereIn('class_id', $classIds)
                ->selectRaw('class_id, COUNT(DISTINCT user_id) as total')
                ->groupBy('class_id')
                ->pluck('total', 'class_id');

            $latestProgress = $recentSubmissions
                ->groupBy('assignment_id')
                ->map(function ($group) use ($enrollCounts) {
                    $assignment = $group->first()->assignment;
                    if (!$assignment || !$assignment->teaching) {
                        return null;
                    }
                    $teaching = $assignment->teaching;
                    $classId = $teaching->class_id;
                    $classSize = $classId ? ($enrollCounts[$classId] ?? 0) : 0;
                    $submitted = $group->count();
                    $percent = $classSize > 0 ? round(($submitted / max($classSize, 1)) * 100) : null;

                    return [
                        'assignment' => $assignment,
                        'submitted' => $submitted,
                        'classSize' => $classSize,
                        'percent' => $percent,
                        'lastSubmittedAt' => $group->max('submitted_at'),
                    ];
                })
                ->filter()
                ->sortByDesc('lastSubmittedAt')
                ->values()
                ->take(3);
        }

        // Aktivitas terbaru siswa dari ActivityLog
        $recentActivities = ActivityLog::with('user.role')
            ->whereHas('user.role', function ($q) {
                $q->where('name', 'siswa');
            })
            ->orderByDesc('timestamp')
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'totalClasses',
            'totalStudents',
            'activeTasks',
            'pendingGrading',
            'upcomingDeadlines',
            'latestProgress',
            'recentActivities'
        ));
    }
}
