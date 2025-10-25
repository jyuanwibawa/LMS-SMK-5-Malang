<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AcademicClassController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'academic_year' => ['required','string','max:20'],
            'level' => ['required','in:X,XI,XII'],
            'major' => ['required','string','max:100'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        SchoolClass::create($data);

        return Redirect::route('admin.academic.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'academic_year' => ['required','string','max:20'],
            'level' => ['required','in:X,XI,XII'],
            'major' => ['required','string','max:100'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $class->update($data);

        return Redirect::route('admin.academic.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return Redirect::route('admin.academic.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function destroyEnrollment(SchoolClass $class, Enrollment $enrollment)
    {
        if ($enrollment->class_id !== $class->id) {
            return Redirect::back()->with('error', 'Enrollment tidak sesuai dengan kelas.');
        }
        $enrollment->delete();
        return Redirect::route('admin.academic.classes.show', $class)->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    public function storeEnrollment(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $exists = Enrollment::where('class_id', $class->id)
            ->where('user_id', $data['user_id'])
            ->exists();
        if ($exists) {
            return Redirect::back()->with('error', 'Siswa sudah terdaftar di kelas ini.');
        }

        Enrollment::create([
            'class_id' => $class->id,
            'user_id' => $data['user_id'],
        ]);

        return Redirect::route('admin.academic.classes.show', $class)->with('success', 'Siswa berhasil ditambahkan.');
    }
}
