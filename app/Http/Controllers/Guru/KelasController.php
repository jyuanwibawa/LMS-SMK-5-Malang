<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teaching;
use App\Models\Material;
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

        $teaching->load(['course', 'schoolClass.enrollments', 'materials', 'assignments']);

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

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            // read file and store as gz-compressed hex in DB
            $raw = $uploaded->get();
            $gz = gzencode($raw, 9);
            $hexData = bin2hex($gz);
            $ext = strtolower($uploaded->getClientOriginalExtension());
            $mime = $uploaded->getMimeType();

            if (empty($ext) && is_string($mime)) {
                             $parts = explode('/', $mime);
                $ext = end($parts) ?: '';
            }

            $videoExts = ['mp4','mov','avi','mkv','webm','m4v'];
            $isVideo = (is_string($mime) && str_starts_with($mime, 'video/')) || in_array($ext, $videoExts, true);
            $fileType = $isVideo ? 'VIDEO' : 'PDF';
            // since we store in DB, do not use file_path for uploaded files
            $filePath = null;
        } elseif (!empty($validated['link'])) {
            $filePath = $validated['link'];
            $fileType = 'LINK';
        }

        Material::create([
            'teaching_id' => $teaching->id,
            'title' => $validated['judul'],
            'description' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_hex' => $hexData ?? null,
            'file_mime' => $mime ?? null,
            'file_name' => isset($uploaded) ? $uploaded->getClientOriginalName() : null,
            'uploaded_at' => now(),
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

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            // store as gz-compressed hex in DB; clear any path
            $raw = $uploaded->get();
            $gz = gzencode($raw, 9);
            $hexData = bin2hex($gz);
            $filePath = null;
            $ext = strtolower($uploaded->getClientOriginalExtension());
            $mime = $uploaded->getMimeType();
            if (empty($ext) && is_string($mime)) {
                $parts = explode('/', $mime);
                $ext = end($parts) ?: '';
            }
            $videoExts = ['mp4','mov','avi','mkv','webm','m4v'];
            $isVideo = (is_string($mime) && str_starts_with($mime, 'video/')) || in_array($ext, $videoExts, true);
            $fileType = $isVideo ? 'VIDEO' : 'PDF';
        } elseif (!empty($validated['link'])) {
            $filePath = $validated['link'];
            $fileType = 'LINK';
            // clear any hex stored previously
            $hexData = null; $mime = null;
        }

        $material->update([
            'title' => $validated['judul'],
            'description' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_hex' => $hexData ?? $material->file_hex,
            'file_mime' => $mime ?? $material->file_mime,
            'file_name' => isset($uploaded) ? $uploaded->getClientOriginalName() : $material->file_name,
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
