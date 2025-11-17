<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\StudentAnswer;
use App\Models\Submission;
use App\Models\Teaching;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SiswaKuisController extends Controller
{
    public function start(Teaching $teaching, Assignment $assignment)
    {
        $this->authorizeAccess($teaching, $assignment);

        // Muat soal dan pilihan
        $assignment->load(['questions.choices']);

        // Cek jika sudah pernah submit
        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', auth()->id())
            ->first();

       return view('siswa.kelas.Tugas.take', compact('teaching','assignment','existing'));
    }

    public function submit(Teaching $teaching, Assignment $assignment, Request $request)
    {
        $this->authorizeAccess($teaching, $assignment);

        // Validasi jawaban: answers[question_id] = choice_id
        $data = $request->validate([
            'answers' => ['required','array','min:1'],
            'answers.*' => ['nullable','integer'],
        ]);

        // Larang submit jika sudah lewat waktu
        if ($assignment->end_time && now()->greaterThan($assignment->end_time)) {
            return back()->withErrors(['kuis' => 'Kuis sudah berakhir.']);
        }

        // Buat / update submission
        $submission = Submission::firstOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => auth()->id()],
            ['submitted_at' => now()]
        );
        if (!$submission->submitted_at) {
            $submission->submitted_at = now();
        }
        $submission->save();

        // Hapus jawaban lama lalu simpan baru
        StudentAnswer::where('submission_id', $submission->id)->delete();

        $assignment->load(['questions.choices']);
        $correct = 0;
        $total = $assignment->questions->count();

        foreach ($assignment->questions as $q) {
            $choiceId = (int) ($data['answers'][$q->id] ?? 0);
            $selected = $q->choices->firstWhere('id', $choiceId);
            $isCorrect = $selected ? (bool)$selected->is_correct : false;
            if ($isCorrect) $correct++;

            StudentAnswer::create([
                'submission_id' => $submission->id,
                'question_id'   => $q->id,
                'choice_id'     => $selected->id ?? null,
                'answer_text'   => null,
            ]);
        }

        // Skor 0-100 sederhana
        $submission->grade = $total > 0 ? round(($correct / $total) * 100) : 0;
        $submission->save();

        // Log aktivitas siswa mengerjakan kuis
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity_type' => 'siswa_submit_kuis',
            'description' => sprintf(
                "Siswa %s mengerjakan kuis '%s'",
                auth()->user()->name,
                $assignment->title
            ),
            'timestamp' => now(),
        ]);

        return redirect()->route('siswa.kuis.result', [$teaching, $assignment])->with('status', 'Kuis disimpan');
    }

    public function result(Teaching $teaching, Assignment $assignment)
    {
        $this->authorizeAccess($teaching, $assignment);

        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', auth()->id())
            ->with(['answers.question.choices'])
            ->first();

        return view('siswa.kelas.Tugas.result', [
    'teaching'   => $teaching,
    'assignment' => $assignment->load('questions.choices'),
    'submission' => $submission,
]);
    }

    private function authorizeAccess(Teaching $teaching, Assignment $assignment): void
    {
        $userId = auth()->id();
        $isEnrolled = \App\Models\Enrollment::where('user_id', $userId)
            ->where('class_id', $teaching->class_id)
            ->exists();
        abort_unless($isEnrolled, 403);
        abort_unless($assignment->teaching_id === $teaching->id, 404);
        abort_unless($assignment->type === 'KUIS', 404);
    }
}