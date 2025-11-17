<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GuruProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        // Semua kelas yang diajar guru ini
        $teachings = Teaching::with(['schoolClass.enrollments', 'course', 'materials'])
            ->where('user_id', $user->id)
            ->get();

        $classIds = $teachings->pluck('class_id')->filter()->unique();

        $totalClasses = $teachings->count();
        $totalStudents = $classIds->isEmpty()
            ? 0
            : Enrollment::whereIn('class_id', $classIds)
                ->distinct('user_id')
                ->count('user_id');

        $totalMaterials = $teachings->pluck('materials')->flatten()->count();

        // Mata pelajaran yang diajar (nama course unik)
        $subjects = $teachings->pluck('course.name')->filter()->unique()->values();

        return view('guru.profil', compact(
            'user',
            'totalClasses',
            'totalStudents',
            'totalMaterials',
            'subjects'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()->route('guru.profil.show')
            ->with('status', 'Profil berhasil diperbarui.');
    }
}