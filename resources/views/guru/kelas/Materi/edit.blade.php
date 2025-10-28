<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #111827; margin: 0; }
        .main-content { margin-left:280px; padding:40px; box-sizing:border-box; }
        .container { max-width: 900px; margin: 0 auto; }
        .page-header { margin-bottom: 24px; }
        .back-link { display:inline-flex; align-items:center; gap:6px; text-decoration:none; color:#4B5563; font-weight:500; font-size:0.9rem; margin-bottom:16px; }
        .back-link:hover { color:#111827; }
        .back-link svg { width:18px; height:18px; }
        .page-header h1 { font-size:2rem; font-weight:700; margin:0 0 8px 0; color:#111827; }
        .page-header p { font-size:1rem; color:#6B7281; margin:0; }
        .form-container { background-color:#ffffff; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.05); padding:32px 40px; }
        .form-group { margin-bottom:24px; }
        .form-group label { display:block; font-weight:600; font-size:0.9rem; color:#374151; margin-bottom:8px; }
        .form-input, .form-textarea { width:100%; padding:12px 16px; border:1px solid #D1D5DB; border-radius:8px; font-size:0.9rem; font-family:'Inter', sans-serif; box-sizing:border-box; transition:border-color .2s, box-shadow .2s; }
        .form-textarea { resize:vertical; min-height:100px; }
        .file-hint { font-size: 0.85rem; color:#6B7281; margin-top:6px; }
        .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:32px; padding-top:24px; border-top:1px solid #E5E7EB; }
        .btn { padding:12px 20px; font-weight:600; font-size:0.9rem; border-radius:8px; border:none; cursor:pointer; transition:all .2s; text-decoration:none; }
        .btn-primary { background-color:#6B7281; color:#ffffff; display:inline-flex; align-items:center; gap:8px; }
        .btn-primary:hover { background-color:#4B5563; }
        .btn-secondary { background-color:#ffffff; color:#374151; border:1px solid #D1D5DB; }
        .btn-secondary:hover { background-color:#F3F4F6; }
    </style>
</head>
<body>
@include('partials._sidebar-guru')
<main class="main-content">
    <div class="container">
        <header class="page-header">
            <a href="{{ route('guru.kelas.show', $teaching) }}" class="back-link">
                ← Kembali ke Materi
            </a>
            <h1>Edit Materi</h1>
            <p>Perbarui informasi materi pembelajaran</p>
        </header>

        <section class="form-container">
            <form action="{{ route('guru.kelas.materi.update', [$teaching, $material]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="judul">Judul Materi</label>
                    <input type="text" id="judul" name="judul" class="form-input" value="{{ old('judul', $material->title) }}" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="form-textarea">{{ old('deskripsi', $material->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="file">Ganti File (opsional)</label>
                    <input type="file" id="file" name="file" class="form-input">
                    @if($material->file_type !== 'LINK' && $material->file_path)
                        <div class="file-hint">File saat ini: {{ basename($material->file_path) }}</div>
                    @else
                        <div class="file-hint">Materi ini berupa tautan.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="link">Ganti Link (opsional)</label>
                    <input type="url" id="link" name="link" class="form-input" value="{{ old('link', $material->file_type === 'LINK' ? $material->file_path : '') }}" placeholder="https://...">
                </div>

                <div class="form-actions">
                    <a href="{{ route('guru.kelas.show', $teaching) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </div>
</main>
</body>
</html>
