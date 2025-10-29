<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #111827; margin: 0; }
        .main-content { margin-left: 280px; padding: 40px; box-sizing: border-box; }

        .container { max-width: 1100px; margin: 0 auto; }
        .page-header { margin-bottom: 24px; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #4B5563; font-weight: 500; font-size: 0.9rem; margin-bottom: 16px; }
        .back-link:hover { color: #111827; }
        .back-link svg { width: 18px; height: 18px; }
        .page-header h1 { font-size: 2.25rem; font-weight: 700; margin: 0; color: #111827; }
        .page-header .subtitle { font-size: 1rem; color: #6B7281; margin: 8px 0 0 0; }

        .tabs { display: flex; background-color: #F3F4F6; border-radius: 12px; padding: 5px; margin-bottom: 32px; overflow-x: auto; }
        .tabs a { text-decoration: none; color: #4B5563; font-weight: 600; font-size: 0.9rem; padding: 10px 24px; border-radius: 8px; transition: all 0.2s ease-in-out; white-space: nowrap; }
        .tabs a:hover { background-color: #E5E7EB; color: #111827; }
        .tabs a.active { background-color: #ffffff; color: #111827; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .content-section { width: 100%; }
        .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .content-header h2 { font-size: 1.5rem; font-weight: 600; margin: 0; }
        .content-header p { color: #6B7281; margin: 4px 0 0 0; font-size: 0.9rem; }

        .btn-upload { background-color: #111827; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.9rem; padding: 12px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; transition: background-color 0.2s; border: none; cursor: pointer; }
        .btn-upload:hover { background-color: #374151; }
        .btn-upload svg { width: 20px; height: 20px; }

        .material-list { display: flex; flex-direction: column; gap: 16px; }
        .material-item { display: flex; align-items: center; gap: 16px; background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .item-icon { color: #4B5563; flex-shrink: 0; }
        .item-icon svg { width: 24px; height: 24px; }
        .item-details { flex-grow: 1; }
        .item-details h3 { font-size: 1.125rem; font-weight: 600; margin: 0 0 4px 0; color: #111827; }
        .item-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; color: #6B7281; font-size: 0.875rem; }
        .item-meta .separator { font-size: 0.5rem; opacity: 0.5; line-height: 1; }
        .item-actions { display: flex; gap: 12px; flex-shrink: 0; }
        .btn-icon { color: #6B7281; text-decoration: none; padding: 4px; display: block; }
        .btn-icon svg { width: 20px; height: 20px; stroke-width: 2; }
        .btn-icon:hover { color: #111827; }
        .btn-icon.btn-danger { color: #EF4444; }
        .btn-icon.btn-danger:hover { color: #DC2626; }
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
                <h1>{{ $title ?? 'Nama Mata Pelajaran - Nama Kelas' }}</h1>
                <p class="subtitle">{{ $subtitle ?? '0 siswa terdaftar' }}</p>
            </header>

            @php($activeTab = request('tab', 'materi'))
            <nav class="tabs">
              <a href="{{ route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'materi']) }}" class="{{ $activeTab === 'materi' ? 'active' : '' }}">Materi</a>
              <a href="{{ route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="{{ $activeTab === 'tugas' ? 'active' : '' }}">Tugas/Kuis</a>
              <a href="#">Nilai</a>
              <a href="#">Forum</a>
              <a href="#">Peserta</a>
            </nav>

            <div id="tab-materi" style="display: {{ $activeTab === 'materi' ? 'block' : 'none' }};">
              @include('guru.kelas.Materi.index')
            </div>

            <div id="tab-tugas" style="display: {{ $activeTab === 'tugas' ? 'block' : 'none' }};">
              @include('guru.kelas.Tugas.index')
            </div>
          
        </div>
    </main>
</body>
</html>
