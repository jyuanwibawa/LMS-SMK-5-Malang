@include('partials._sidebar-siswa')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelas Saya</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:40px; box-sizing:border-box; }
    .page-header { margin-bottom:24px; }
    .page-header h1 { margin:0 0 6px 0; color:#1a202c; font-weight:700; }
    .page-header p { margin:0; color:#4a5568; }

    .grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:20px; }
    @media (max-width: 1200px) { .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 800px) { .grid { grid-template-columns: 1fr; } }

    .class-card { background:#fff; border:1px solid #E5E7EB; border-radius:18px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,.06); display:flex; flex-direction:column; }
    .card-top { padding:18px 20px; color:#fff; }
    .card-title { margin:0 0 4px 0; font-size:20px; font-weight:700; }
    .card-subtitle { margin:0; font-size:14px; opacity:.95; }
    .card-body { padding:18px 20px; }
    .row { display:flex; align-items:center; gap:10px; color:#6b7280; font-size:14px; margin-bottom:10px; }
    .chips { display:flex; gap:10px; margin-top:10px; }
    .chip { background:#fff; border:1px solid #E5E7EB; border-radius:9999px; padding:6px 10px; font-weight:700; font-size:12px; color:#111827; box-shadow:0 1px 2px rgba(0,0,0,.03); }
    .card-footer { padding:18px 20px; display:flex; justify-content:space-between; align-items:center; }
    .btn-primary { background:#111827; color:#fff; padding:12px 16px; border-radius:12px; text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-weight:700; }
  </style>
</head>
<body>
  <main class="main">
    <header class="page-header">
      <h1>Kelas Saya</h1>
      <p>Daftar mata pelajaran di kelas yang Anda ikuti.</p>
    </header>

    @php
      $colors = ['#2563EB', '#10B981', '#8B5CF6', '#DC2626', '#0EA5E9'];
    @endphp

    @if($teachings->isEmpty())
      <p style="color:#6b7280">Anda belum terdaftar pada kelas manapun.</p>
    @else
      <section class="grid">
        @foreach($teachings as $i => $t)
          @php($color = $colors[$i % count($colors)])
          <article class="class-card">
            <div class="card-top" style="background: {{ $color }};">
              <h3 class="card-title">{{ $t->course->name }} {{ $t->schoolClass->name }}</h3>
              <p class="card-subtitle">{{ $t->user->name }}</p>
            </div>
            <div class="card-body">
              <div class="row">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Jadwal: -</span>
              </div>
              <div class="row">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>{{ $studentsPerClass[$t->class_id] ?? '-' }} siswa</span>
              </div>
              <div class="chips">
                <span class="chip">{{ $t->materials_count }} Materi</span>
                <span class="chip">{{ $t->assignments_count }} Tugas</span>
              </div>
            </div>
            <div class="card-footer">
              <a href="{{ route('siswa.kelas.show', $t) }}" class="btn-primary">Masuk Kelas
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
              </a>
            </div>
          </article>
        @endforeach
      </section>
    @endif
  </main>
</body>
</html>
