<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materi Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #111827; margin: 0; }
        .main-content { margin-left:280px; padding:40px; box-sizing:border-box; }

        .container { max-width: 900px; margin: 0 auto; }
        .page-header { margin-bottom: 24px; }
        .back-link { display:inline-flex; align-items:center; gap:6px; text-decoration:none; color:#4B5563; font-weight:500; font-size:0.9rem; margin-bottom:16px; }
        .back-link:hover { color:#111827; }
        .back-link svg { width:18px; height:18px; }
        .page-header h1 { font-size:2.25rem; font-weight:700; margin:0 0 8px 0; color:#111827; }
        .page-header p { font-size:1rem; color:#6B7281; margin:0; }

        .form-container { background-color:#ffffff; border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.05); padding:32px 40px; }
        .form-container h2 { font-size:1.25rem; font-weight:600; margin:0 0 28px 0; color:#111827; }

        .form-group { margin-bottom:24px; }
        .form-group label { display:block; font-weight:600; font-size:0.9rem; color:#374151; margin-bottom:8px; }
        .form-input, .form-textarea { width:100%; padding:12px 16px; border:1px solid #D1D5DB; border-radius:8px; font-size:0.9rem; font-family:'Inter', sans-serif; box-sizing:border-box; transition:border-color .2s, box-shadow .2s; }
        .form-input:focus, .form-textarea:focus { outline:none; border-color:#3B82F6; box-shadow:0 0 0 2px rgba(59,130,246,0.2); }
        .form-input::placeholder, .form-textarea::placeholder { color:#9CA3AF; }
        .form-textarea { resize:vertical; min-height:100px; }

        .file-upload-box { border:2px dashed #D1D5DB; border-radius:12px; background-color:#fcfcfd; padding:32px; text-align:center; cursor:pointer; transition:all .2s; }
        .file-upload-box:hover { border-color:#3B82F6; background-color:#f7f9ff; }
        .file-upload-box svg { width:48px; height:48px; color:#6B7281; margin-bottom:12px; }
        .file-upload-box strong { display:block; font-weight:600; color:#111827; font-size:1rem; }
        .file-upload-box p { font-size:0.875rem; color:#6B7281; margin:4px 0 0 0; }
        .file-upload-input { display:none; }

        .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:32px; padding-top:24px; border-top:1px solid #E5E7EB; }
        .btn { padding:12px 20px; font-weight:600; font-size:0.9rem; border-radius:8px; border:none; cursor:pointer; transition:all .2s; text-decoration:none; }
        .btn-primary { background-color:#6B7281; color:#ffffff; display:inline-flex; align-items:center; gap:8px; }
        .btn-primary:hover { background-color:#4B5563; }
        .btn-primary svg { width:18px; height:18px; }
        .btn-secondary { background-color:#ffffff; color:#374151; border:1px solid #D1D5DB; }
        .btn-secondary:hover { background-color:#F3F4F6; }
    </style>
</head>
<body>
    @include('partials._sidebar-guru')

    <main class="main-content">
        <div class="container">
            <header class="page-header">
                <a href="{{ url()->previous() }}" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <h1>Upload Materi Baru</h1>
                <p>Tambahkan materi pembelajaran untuk kelas Anda</p>
            </header>

            <main class="form-container">
                <form action="{{ route('guru.kelas.materi.store', $teaching) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h2>Informasi Materi</h2>

                    <div class="form-group">
                        <label for="judul">Judul Materi</label>
                        <input type="text" id="judul" name="judul" class="form-input" placeholder="Contoh: Pengenalan Trigonometri">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="form-textarea" placeholder="Jelaskan isi materi..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload File</label>
                        <label for="file-upload" class="file-upload-box">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <strong>Klik untuk upload file</strong>
                            <p>PDF, PPT, DOC, Video (Max: 50MB)</p>
                        </label>
                        <input type="file" id="file-upload" name="file" class="file-upload-input">
                    </div>

                    <div class="form-group">
                        <label for="link">Link Eksternal (Opsional)</label>
                        <input type="text" id="link" name="link" class="form-input" placeholder="https://...">
                    </div>

                    <div class="form-actions">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Upload Materi
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </main>
</body>
</html>
