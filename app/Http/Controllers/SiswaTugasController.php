<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\Teaching;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaTugasController extends Controller
{
    public function create(Teaching $teaching, Assignment $assignment)
    {
        $userId = auth()->id();

        // Validasi akses
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        abort_unless($assignment->type === 'TUGAS', 404);

        // Cek submission yang sudah ada milik user
        $assignment->loadMissing(['submissions' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }]);
        $existing = $assignment->submissions->first();

        return view('siswa.kelas.Tugas.submit', [
            'teaching' => $teaching,
            'assignment' => $assignment,
            'existing' => $existing,
        ]);
    }

    public function store(Teaching $teaching, Assignment $assignment, Request $request)
    {
        $userId = auth()->id();

        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        abort_unless($assignment->type === 'TUGAS', 404);

        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10 MB
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        // Satu submission per user per assignment: update jika sudah ada
        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            if ($existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->update([
                'file_path' => $path,
                'submitted_at' => now(),
            ]);
            $submission = $existing;
        } else {
            $submission = Submission::create([
                'assignment_id' => $assignment->id,
                'user_id' => $userId,
                'file_path' => $path,
                'submitted_at' => now(),
            ]);
        }

        // Log aktivitas siswa submit tugas
        ActivityLog::create([
            'user_id' => $userId,
            'activity_type' => 'siswa_submit_tugas',
            'description' => sprintf(
                "Siswa %s mengumpulkan tugas '%s'",
                auth()->user()->name,
                $assignment->title
            ),
            'timestamp' => now(),
        ]);

        return redirect()->route('siswa.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas'])
            ->with('status', 'Tugas berhasil dikumpulkan');
    }
}