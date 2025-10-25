<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teaching;
use Illuminate\Http\Request;

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
}
