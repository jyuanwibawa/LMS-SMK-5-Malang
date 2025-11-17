<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Teaching;
use App\Models\SchoolClass;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;

class SiswaKelasController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $enrollments = Enrollment::where('user_id', $userId)->pluck('class_id');

        if ($enrollments->isEmpty()) {
            return view('siswa.kelas.index', [
                'teachings' => collect(),
                'studentsPerClass' => [],
            ]);
        }

        // Ajukan jumlah siswa per kelas
        $studentsPerClass = Enrollment::selectRaw('class_id, COUNT(*) as total')
            ->whereIn('class_id', $enrollments)
            ->groupBy('class_id')
            ->pluck('total', 'class_id')
            ->toArray();

        // Ambil semua pengajaran (mapel) pada kelas yang diikuti siswa
        $teachings = Teaching::with([ 'course', 'user', 'schoolClass' ])
            ->withCount([ 'materials', 'assignments' ])
            ->whereIn('class_id', $enrollments)
            ->orderBy('class_id')
            ->get();

        return view('siswa.kelas.index', compact('teachings', 'studentsPerClass'));
    }

    public function show(Teaching $teaching, Request $request)
    {
        $userId = auth()->id();
        // Pastikan siswa terdaftar di kelas teaching ini
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);

        $teaching->load(['course', 'user', 'schoolClass']);
        $materials = Material::where('teaching_id', $teaching->id)
            ->orderByDesc('uploaded_at')->orderByDesc('id')->get();
        $activeTab = in_array($request->query('tab'), ['materi','tugas','nilai','forum']) ? $request->query('tab') : 'materi';

        // Data untuk tab Tugas dan Kuis (menggunakan submissions milik user saat ini)
        $assignments = Assignment::with(['submissions' => function ($q) use ($userId) {
                $q->where('user_id', $userId)->orderByDesc('submitted_at');
            }])
            ->where('teaching_id', $teaching->id)
            ->where('type', 'TUGAS')
            ->orderByDesc('start_time')
            ->orderByDesc('id')
            ->get();

        $quizzes = Assignment::with(['submissions' => function ($q) use ($userId) {
                $q->where('user_id', $userId)->orderByDesc('submitted_at');
            }])
            ->where('teaching_id', $teaching->id)
            ->where('type', 'KUIS')
            ->orderByDesc('start_time')
            ->orderByDesc('id')
            ->get();

        return view('siswa.kelas.detail', compact('teaching','activeTab','materials','assignments','quizzes'));
    }

    public function materiDownload(Teaching $teaching, Material $material)
    {
        $userId = auth()->id();
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            $downloadName = $material->file_name ?: basename($material->file_path);
            return Storage::disk('public')->download($material->file_path, $downloadName);
        }
        return back()->withErrors(['file' => 'File tidak ditemukan.']);
    }

    public function materiView(Teaching $teaching, Material $material)
    {
        $userId = auth()->id();
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            $headers = [];
            if (!empty($material->file_mime)) {
                $headers['Content-Type'] = $material->file_mime;
            }
            return Storage::disk('public')->response($material->file_path, $material->file_name ?: basename($material->file_path), $headers);
        }
        return back()->withErrors(['file' => 'File tidak tersedia untuk pratinjau.']);
    }
}
