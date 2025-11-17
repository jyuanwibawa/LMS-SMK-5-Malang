<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teaching;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelasController extends Controller
{
    public function index()
    {
        $teachings = Teaching::with([
                'course',
                'schoolClass.enrollments',
            ])
            ->withCount(['materials', 'assignments'])
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('guru.kelas.index', compact('teachings'));
    }

    public function show(Teaching $teaching)
    {
        // Authorize: owner or admin
        abort_unless($this->canAccess($teaching), 403);

        $teaching->load([
            'course',
            'schoolClass.enrollments.user',
            'materials',
            'assignments.submissions.user',
        ]);

        $title = $teaching->course->name . ' - ' . $teaching->schoolClass->name;
        $subtitle = $teaching->schoolClass->enrollments->count() . ' siswa terdaftar';

        return view('guru.kelas.detail', compact('teaching', 'title', 'subtitle'));
    }

    public function materi(Teaching $teaching)
    {
        abort_unless($this->canAccess($teaching), 403);
        $teaching->load(['course', 'schoolClass']);

        return view('guru.kelas.materi.index', compact('teaching'));
    }

    public function materiCreate(Teaching $teaching)
    {
        abort_unless($this->canAccess($teaching), 403);
        $teaching->load(['course', 'schoolClass']);

        return view('guru.kelas.Materi.tambahmateri', compact('teaching'));
    }

    public function materiStore(Teaching $teaching, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:51200'],
            'link' => ['nullable', 'url'],
        ]);

        if (!$request->hasFile('file') && empty($validated['link'] ?? null)) {
            return back()
                ->withErrors(['file' => 'Harap unggah file atau isi tautan.'])
                ->withInput();
        }

        $filePath = null;
        $fileType = null;
        $mime = null;
        $uploaded = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $mime = $uploaded->getMimeType();
            $ext = strtolower($uploaded->getClientOriginalExtension() ?? '');
            if (empty($ext) && is_string($mime)) {
                $parts = explode('/', $mime);
                $ext = end($parts) ?: '';
            }
            // Simpan ke disk public dan catat path
            $dir = 'materials/'.$teaching->id;
            $filePath = $uploaded->store($dir, 'public');
            $videoExts = ['mp4','mov','avi','mkv','webm','m4v'];
            $isVideo = (is_string($mime) && str_starts_with($mime, 'video/')) || in_array($ext, $videoExts, true);
            $fileType = $isVideo ? 'VIDEO' : 'PDF';
        } elseif (!empty($validated['link'])) {
            $filePath = $validated['link'];
            $fileType = 'LINK';
        }

        $material = Material::create([
            'teaching_id' => $teaching->id,
            'title' => $validated['judul'],
            'description' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_hex' => null,
            'file_mime' => $mime ?? null,
            'file_name' => $uploaded?->getClientOriginalName(),
            'uploaded_at' => now(),
        ]);

        // Log aktivitas guru upload materi
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity_type' => 'guru_upload_materi',
            'description' => sprintf(
                "Guru %s mengunggah materi '%s' untuk %s - %s",
                auth()->user()->name,
                $material->title,
                optional($teaching->course)->name,
                optional($teaching->schoolClass)->name
            ),
            'timestamp' => now(),
        ]);

        return redirect()->route('guru.kelas.show', $teaching)->with('status', 'Materi berhasil diunggah');
    }

    public function materiEdit(Teaching $teaching, Material $material)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        $teaching->load(['course', 'schoolClass']);
        return view('guru.kelas.Materi.edit', compact('teaching', 'material'));
    }

    public function materiUpdate(Teaching $teaching, Material $material, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:51200'],
            'link' => ['nullable', 'url'],
        ]);

        $filePath = $material->file_path;
        $fileType = $material->file_type;
        $mime = $material->file_mime;
        $uploaded = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $mime = $uploaded->getMimeType();
            $ext = strtolower($uploaded->getClientOriginalExtension() ?? '');
            if (empty($ext) && is_string($mime)) {
                $parts = explode('/', $mime);
                $ext = end($parts) ?: '';
            }
            $dir = 'materials/'.$teaching->id;
            $filePath = $uploaded->store($dir, 'public');
            $videoExts = ['mp4','mov','avi','mkv','webm','m4v'];
            $isVideo = (is_string($mime) && str_starts_with($mime, 'video/')) || in_array($ext, $videoExts, true);
            $fileType = $isVideo ? 'VIDEO' : 'PDF';
        } elseif (!empty($validated['link'])) {
            $filePath = $validated['link'];
            $fileType = 'LINK';
            $mime = null;
        }

        $material->update([
            'title' => $validated['judul'],
            'description' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_hex' => null,
            'file_mime' => $mime,
            'file_name' => $uploaded?->getClientOriginalName() ?? $material->file_name,
        ]);

        return redirect()->route('guru.kelas.show', $teaching)->with('status', 'Materi berhasil diperbarui');
    }

    public function materiDestroy(Teaching $teaching, Material $material)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        if ($material->file_path && $material->file_type !== 'LINK') {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        return back()->with('status', 'Materi berhasil dihapus');
    }

    public function materiDownload(Teaching $teaching, Material $material)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        if ($material->file_type === 'LINK') {
            return redirect()->away($material->file_path);
        }

        // serve from DB if hex available
        if (!empty($material->file_hex)) {
            $binary = gzdecode(hex2bin($material->file_hex));
            $filename = $material->file_name ?: ('materi-'.($material->id).'.bin');
            return response($binary)
                ->header('Content-Type', $material->file_mime ?: 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        }

        // fallback to storage path if exists
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            $filename = basename($material->file_path);
            return Storage::disk('public')->download($material->file_path, $filename);
        }

        return back()->withErrors(['file' => 'File tidak ditemukan.']);
    }

    public function tugasCreate(Teaching $teaching)
    {
        abort_unless($this->canAccess($teaching), 403);
        $teaching->load(['course', 'schoolClass']);
        return view('guru.kelas.Tugas.buat_tugas', compact('teaching'));
    }

    public function tugasStore(Teaching $teaching, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'start_time' => ['nullable','date'],
            'end_time' => ['nullable','date','after_or_equal:start_time'],
        ]);

        $assignment = Assignment::create([
            'teaching_id' => $teaching->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => 'TUGAS',
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
        ]);

        // Otomatis buat pengumuman untuk siswa
        Announcement::create([
            'user_id' => auth()->id(),
            'class_id' => $teaching->class_id,
            'title' => 'Tugas baru: ' . $assignment->title,
            'content' => rtrim(($assignment->description ?? 'Tugas baru telah ditambahkan.') . (
                $assignment->end_time ? ("\n\nDeadline: " . $assignment->end_time->format('d M Y H:i')) : ''
            )),
            'created_at' => now(),
        ]);

        // Log aktivitas guru membuat tugas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity_type' => 'guru_buat_tugas',
            'description' => sprintf(
                "Guru %s membuat tugas '%s' untuk %s - %s",
                auth()->user()->name,
                $assignment->title,
                optional($teaching->course)->name,
                optional($teaching->schoolClass)->name
            ),
            'timestamp' => now(),
        ]);

        return redirect()->route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas'])
            ->with('status', 'Tugas/Kuis berhasil dibuat');
    }

    public function kuisCreate(Teaching $teaching)
    {
        abort_unless($this->canAccess($teaching), 403);
        $teaching->load(['course', 'schoolClass']);
        return view('guru.kelas.Tugas.tambah_kuis', compact('teaching'));
    }

    public function kuisStore(Teaching $teaching, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'start_time' => ['nullable','date'],
            'end_time' => ['nullable','date','after_or_equal:start_time'],
            'questions' => ['required','array','min:1'],
            'questions.*.question_text' => ['required','string'],
            'questions.*.choices' => ['required','array','min:2'],
            'questions.*.choices.*.choice_text' => ['required','string'],
            'questions.*.correct_index' => ['required','integer','min:0'],
        ]);

        $assignment = Assignment::create([
            'teaching_id' => $teaching->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => 'KUIS',
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
        ]);

        // Otomatis buat pengumuman untuk siswa
        Announcement::create([
            'user_id' => auth()->id(),
            'class_id' => $teaching->class_id,
            'title' => 'Kuis baru: ' . $assignment->title,
            'content' => rtrim(($assignment->description ?? 'Kuis baru telah ditambahkan.') . (
                $assignment->end_time ? ("\n\nDeadline: " . $assignment->end_time->format('d M Y H:i')) : ''
            )),
            'created_at' => now(),
        ]);

        // Log aktivitas guru membuat kuis
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity_type' => 'guru_buat_kuis',
            'description' => sprintf(
                "Guru %s membuat kuis '%s' untuk %s - %s",
                auth()->user()->name,
                $assignment->title,
                optional($teaching->course)->name,
                optional($teaching->schoolClass)->name
            ),
            'timestamp' => now(),
        ]);

        foreach ($validated['questions'] as $qIndex => $qData) {
            $question = \App\Models\Question::create([
                'assignment_id' => $assignment->id,
                'question_text' => $qData['question_text'],
                'question_type' => 'pilihan_ganda',
            ]);

            foreach ($qData['choices'] as $cIndex => $cData) {
                \App\Models\Choice::create([
                    'question_id' => $question->id,
                    'choice_text' => $cData['choice_text'],
                    'is_correct' => $cIndex === (int)($qData['correct_index'] ?? -1),
                ]);
            }
        }

        return redirect()->route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas'])
            ->with('status', 'Kuis berhasil dibuat');
    }

    public function tugasEdit(Teaching $teaching, Assignment $assignment)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        $teaching->load(['course', 'schoolClass']);
        return view('guru.kelas.Tugas.edit', compact('teaching', 'assignment'));
    }

    public function tugasUpdate(Teaching $teaching, Assignment $assignment, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'start_time' => ['nullable','date'],
            'end_time' => ['nullable','date','after_or_equal:start_time'],
        ]);

        $assignment->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
        ]);

        return redirect()->route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas'])
            ->with('status', 'Tugas berhasil diperbarui');
    }

    public function tugasDestroy(Teaching $teaching, Assignment $assignment)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);

        $assignment->delete();
        return redirect()->route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas'])
            ->with('status', 'Tugas berhasil dihapus');
    }

    public function tugasNilai(Teaching $teaching, Assignment $assignment, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);

        $teaching->load(['course', 'schoolClass']);
        $assignment->load(['submissions.user']);

        $submissionId = $request->query('submission');
        $currentSubmission = null;
        if ($submissionId) {
            $currentSubmission = $assignment->submissions->firstWhere('id', (int) $submissionId);
        }
        if (!$currentSubmission) {
            $currentSubmission = $assignment->submissions->sortBy('submitted_at')->first();
        }

        return view('guru.kelas.Tugas.nilai_tugas', [
            'teaching' => $teaching,
            'assignment' => $assignment,
            'currentSubmission' => $currentSubmission,
        ]);
    }

    public function submissionDownload(Teaching $teaching, Assignment $assignment, Submission $submission)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        abort_unless($submission->assignment_id === $assignment->id, 404);

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            $filename = basename($submission->file_path);
            return Storage::disk('public')->download($submission->file_path, $filename);
        }

        return back()->withErrors(['file' => 'File tidak ditemukan.']);
    }

    public function submissionGradeUpdate(Teaching $teaching, Assignment $assignment, Submission $submission, Request $request)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $data = $request->validate([
            'nilai' => ['nullable','numeric','min:0','max:100'],
            'feedback' => ['nullable','string'],
        ]);

        $submission->update([
            'grade' => $data['nilai'] ?? null,
            'feedback' => $data['feedback'] ?? null,
        ]);

        return redirect()->route('guru.kelas.tugas.nilai', [
            'teaching' => $teaching,
            'assignment' => $assignment,
            'submission' => $submission->id,
        ])->with('status', 'Nilai tersimpan');
    }

    public function materiView(Teaching $teaching, Material $material)
    {
        abort_unless($this->canAccess($teaching), 403);
        abort_unless($material->teaching_id === $teaching->id, 404);

        if ($material->file_type === 'LINK') {
            return redirect()->away($material->file_path);
        }

        if (!empty($material->file_hex)) {
            $binary = gzdecode(hex2bin($material->file_hex));
            $filename = $material->file_name ?: ('materi-'.($material->id).'.bin');
            return response($binary)
                ->header('Content-Type', $material->file_mime ?: 'application/octet-stream')
                ->header('Content-Disposition', 'inline; filename="'.$filename.'"');
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            // attempt to stream inline
            return response()->file(Storage::disk('public')->path($material->file_path));
        }

        return back()->withErrors(['file' => 'File tidak ditemukan.']);
    }

    private function canAccess(Teaching $teaching): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        $isOwner = $teaching->user_id === $user->id;
        $isAdmin = optional($user->role)->name === 'admin';
        return $isOwner || $isAdmin;
    }
}
