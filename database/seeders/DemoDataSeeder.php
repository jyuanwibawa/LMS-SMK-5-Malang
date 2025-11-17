<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Teaching;
use App\Models\Assignment;
use App\Models\Submission;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        foreach (['admin','guru','siswa'] as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Get a guru and some siswa
        $guru = User::whereHas('role', fn($q)=>$q->where('name','guru'))->first();
        if (!$guru) {
            $guru = User::firstOrCreate(
                ['email' => 'guru-demo@smk5malang.sch.id'],
                [
                    'name' => 'Guru Demo',
                    'identity_number' => '1987654321',
                    'jenis_kelamin' => 'Laki-Laki',
                    'password' => Hash::make('password'),
                    'role_id' => Role::where('name','guru')->value('id'),
                ]
            );
        }

        $siswaRoleId = Role::where('name','siswa')->value('id');
        $siswaUsers = User::where('role_id', $siswaRoleId)->take(3)->get();
        if ($siswaUsers->count() < 3) {
            // create additional students to reach 3
            for ($i = $siswaUsers->count(); $i < 3; $i++) {
                $siswaUsers->push(User::create([
                    'name' => 'Siswa Demo '.($i+1),
                    'email' => 'siswa_demo'.($i+1).'@smk5malang.sch.id',
                    'identity_number' => '99000'.($i+1),
                    'jenis_kelamin' => $i % 2 === 0 ? 'Laki-Laki' : 'Perempuan',
                    'password' => Hash::make('password'),
                    'role_id' => $siswaRoleId,
                ]));
            }
        }

        // Create class and course
        $class = SchoolClass::firstOrCreate(
            ['name' => 'XI RPL 1', 'academic_year' => '2025/2026'],
            ['level' => 'XI', 'major' => 'RPL', 'is_active' => true]
        );

        $course = Course::firstOrCreate(
            ['name' => 'Matematika'],
            ['description' => 'Mata pelajaran Matematika']
        );

        // Enroll students to class
        foreach ($siswaUsers as $u) {
            Enrollment::firstOrCreate([
                'user_id' => $u->id,
                'class_id' => $class->id,
            ]);
        }

        // Create teaching (guru mengajar kelas & mapel)
        $teaching = Teaching::firstOrCreate([
            'user_id' => $guru->id,
            'class_id' => $class->id,
            'course_id' => $course->id,
        ]);

        // Create assignments (TUGAS & KUIS per enum)
        $now = now();
        $tugas = Assignment::firstOrCreate([
            'teaching_id' => $teaching->id,
            'title' => 'Latihan Trigonometri',
            'type' => 'TUGAS',
        ], [
            'description' => 'Kerjakan soal-soal trigonometri',
            'start_time' => $now->copy(),
            'end_time' => $now->copy()->addDays(7),
        ]);

        $kuis = Assignment::firstOrCreate([
            'teaching_id' => $teaching->id,
            'title' => 'Kuis Bab Trigonometri',
            'type' => 'KUIS',
        ], [
            'description' => 'Kuis singkat',
            'start_time' => $now->copy()->addDay(),
            'end_time' => $now->copy()->addDays(2),
        ]);

        // Submissions for the tugas
        foreach ($siswaUsers as $idx => $u) {
            Submission::firstOrCreate(
                [
                    'assignment_id' => $tugas->id,
                    'user_id' => $u->id,
                ],
                [
                    'submitted_at' => $now->copy()->addHours(2 + $idx),
                    'file_path' => null,
                    'grade' => $idx === 0 ? 92.00 : null,
                    'feedback' => $idx === 0 ? 'Bagus! Tingkatkan ketelitian' : null,
                ]
            );
        }
    }
}
