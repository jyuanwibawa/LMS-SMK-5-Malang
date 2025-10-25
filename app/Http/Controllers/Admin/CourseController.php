<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teaching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
        ]);

        Course::create($data);

        return Redirect::route('admin.academic.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
        ]);

        $course->update($data);

        return Redirect::route('admin.academic.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return Redirect::route('admin.academic.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function storeTeaching(Request $request, Course $course)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        $exists = Teaching::where('course_id', $course->id)
            ->where('user_id', $data['user_id'])
            ->where('class_id', $data['class_id'])
            ->exists();
        if ($exists) {
            return Redirect::back()->with('error', 'Pengajaran sudah ada.');
        }

        Teaching::create([
            'course_id' => $course->id,
            'user_id' => $data['user_id'],
            'class_id' => $data['class_id'],
        ]);

        return Redirect::route('admin.academic.courses.show', $course)->with('success', 'Guru berhasil ditambahkan pada mata pelajaran.');
    }

    public function destroyTeaching(Course $course, Teaching $teaching)
    {
        if ($teaching->course_id !== $course->id) {
            return Redirect::back()->with('error', 'Data pengajaran tidak sesuai.');
        }
        $teaching->delete();
        return Redirect::route('admin.academic.courses.show', $course)->with('success', 'Pengajaran berhasil dihapus.');
    }
}
