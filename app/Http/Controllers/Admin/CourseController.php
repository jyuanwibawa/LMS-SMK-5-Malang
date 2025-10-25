<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
}
