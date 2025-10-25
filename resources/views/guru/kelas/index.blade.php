<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #1a202c; margin: 0; }
        .main-content { margin-left: 280px; padding: 40px; box-sizing: border-box; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; }
        .container h1 { font-size: 2.25rem; font-weight: 700; margin: 0 0 8px 0; color: #111827; }
        .container > p { font-size: 1rem; color: #6B7281; margin: 0 0 32px 0; }

        /* Grid Kelas */
        .class-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 320px)); gap: 24px; justify-content: start; }

        /* Kartu Kelas */
        .class-card { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; }
        .card-header { padding: 24px; color: #ffffff; }
        .card-header.blue { background-color: #3B82F6; }
        .card-header.purple { background-color: #8B5CF6; }
        .card-header h2 { font-size: 1.5rem; font-weight: 700; margin: 0 0 4px 0; }
        .card-header span { font-size: 1rem; font-weight: 500; opacity: 0.9; }

        .card-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; flex-grow: 1; }
        .info-item { display: flex; align-items: center; gap: 12px; color: #4B5563; }
        .info-item svg { width: 20px; height: 20px; stroke-width: 2; color: #6B7281; flex-shrink: 0; }
        .info-item span { font-size: 0.9rem; font-weight: 500; }

        .card-footer { padding: 24px; background-color: #ffffff; border-top: 1px solid #F3F4F6; }
        .card-stats { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
        .stat-pill { background-color: #F3F4F6; color: #374151; font-size: 0.75rem; font-weight: 600; padding: 6px 12px; border-radius: 9999px; }

        .btn-masuk { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 16px; background-color: #111827; color: #ffffff; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: background-color 0.2s ease-in-out; box-sizing: border-box; }
        .btn-masuk:hover { background-color: #374151; }
        .btn-masuk svg { width: 20px; height: 20px; stroke-width: 2.5; }
    </style>
</head>
<body>
    @include('partials._sidebar-guru')

    <main class="main-content">
        <div class="container">
            <h1>Kelas Saya</h1>
            <p>Daftar semua kelas yang Anda ajar semester ini</p>

            <div class="class-grid">
                @forelse($teachings as $i => $t)
                    <div class="class-card">
                        <div class="card-header {{ $i % 2 === 0 ? 'blue' : 'purple' }}">
                            <h2>{{ $t->course->name }}</h2>
                            <span>{{ $t->schoolClass->name }}{{ $t->schoolClass->academic_year ? ' • '.$t->schoolClass->academic_year : '' }}</span>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Jadwal: -</span>
                            </div>
                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>{{ $t->schoolClass->enrollments->count() }} siswa</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-stats">
                                <span class="stat-pill">{{ $t->materials_count }} Materi</span>
                                <span class="stat-pill">{{ $t->assignments_count }} Tugas</span>
                            </div>
                            <a href="#" class="btn-masuk">
                                Masuk Kelas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <p>Belum ada kelas yang Anda ajar.</p>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>
