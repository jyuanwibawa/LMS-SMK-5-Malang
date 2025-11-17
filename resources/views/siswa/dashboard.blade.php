@include('partials._sidebar-siswa')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Siswa</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:32px 40px; box-sizing:border-box; }
    .wrap { max-width:1400px; margin:0 auto; }

    .h1 { font-size:32px; font-weight:800; color:#111827; margin:0 0 6px; }
    .sub { color:#6b7280; margin:0 0 18px; }

    .stats { display:grid; grid-template-columns: repeat(4, 1fr); gap:16px; }
    .card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:16px; box-shadow:0 1px 2px rgba(0,0,0,.03); display:flex; flex-direction:column; gap:8px; }
    .name { color:#6b7280; font-weight:700; }
    .value { font-size:28px; font-weight:900; color:#111827; }
    .icon { margin-left:auto; width:44px; height:44px; border-radius:12px; background:#111827; display:flex; align-items:center; justify-content:center; color:#ffffff; }
    .icon.orange { background:#111827; color:#ffffff; }
    .icon.green { background:#111827; color:#ffffff; }
    .icon.purple { background:#111827; color:#ffffff; }

    /* Bagian bawah: list tugas/kuis & pengumuman */
    .lower { display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-top:24px; align-items:start; }
    .panel { background:#fff; border:1px solid #E5E7EB; border-radius:18px; padding:18px; }
    .panel h2 { margin:0; font-size:18px; display:flex; align-items:center; gap:10px; color:#111827; }
    .panel .subtitle { margin:6px 0 14px; color:#6b7280; }

    .work-item { display:flex; align-items:center; justify-content:space-between; border:1px solid #E5E7EB; border-radius:16px; padding:14px; }
    .work-info { display:flex; flex-direction:column; gap:6px; }
    .chips { display:flex; gap:8px; flex-wrap:wrap; }
    .chip { background:#F3F4F6; color:#111827; border:1px solid #E5E7EB; border-radius:999px; padding:4px 10px; font-size:12px; font-weight:700; }
    .chip.dark { background:#111827; color:#fff; border-color:#111827; }
    .work-title { font-size:16px; font-weight:700; color:#111827; }
    .deadline { color:#6b7280; font-size:13px; display:flex; align-items:center; gap:8px; }
    .btn-do { background:#111827; color:#fff; border:none; padding:10px 14px; border-radius:12px; text-decoration:none; font-weight:700; }

    .works { display:flex; flex-direction:column; gap:12px; }

    .ann-list { display:flex; flex-direction:column; gap:12px; }
    .ann-item { background:#F9FAFB; border:1px solid #E5E7EB; padding:14px; border-radius:14px; }
    .ann-title { margin:0 0 4px; font-weight:700; color:#111827; font-size:15px; }
    .ann-text { margin:0 0 10px; color:#6b7280; font-size:13px; }
    .ann-meta { display:flex; justify-content:space-between; align-items:center; }
    .ann-chip { background:#EEF2FF; color:#374151; border-radius:999px; padding:4px 10px; font-size:12px; border:1px solid #E5E7EB; }
  </style>
</head>
<body>
  <main class="main">
    <div class="wrap">
      <div class="h1">Selamat Datang, {{ auth()->user()->name }}! <i class='bx bx-smile'></i></div>
      <div class="sub">Berikut adalah ringkasan aktivitas belajar Anda hari ini</div>

      <div class="stats">
        <div class="card">
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="name">Total Kelas</div>
            <div class="icon"><i class='bx bx-book-open'></i></div>
          </div>
          <div class="value">{{ $totalClasses }}</div>
        </div>
        <div class="card">
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="name">Tugas Aktif</div>
            <div class="icon orange"><i class='bx bx-task'></i></div>
          </div>
          <div class="value">{{ $activeTasks }}</div>
        </div>
        <div class="card">
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="name">Tugas Selesai</div>
            <div class="icon green"><i class='bx bx-check-circle'></i></div>
          </div>
          <div class="value">{{ $tasksDone }}</div>
        </div>
        <div class="card">
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="name">Rata-rata Nilai</div>
            <div class="icon purple"><i class='bx bx-line-chart'></i></div>
          </div>
          <div class="value">{{ $avgGrade === null ? '-' : $avgGrade }}</div>
        </div>
      </div>

      <div class="lower">
        <section class="panel">
          <h2><i class='bx bx-time-five'></i> Tugas & Kuis Mendatang</h2>
          <div class="subtitle">Deadline terdekat yang perlu segera dikerjakan</div>

          <div class="works">
            @forelse($upcomingWorks as $w)
              @php($hours = now()->diffInHours($w->end_time, false))
              <div class="work-item">
                <div class="work-info">
                  <div class="chips">
                    <span class="chip dark">{{ strtoupper($w->type ?? '-') }}</span>
                    <span class="chip">{{ $w->teaching->course->name ?? 'Mata Pelajaran' }}</span>
                  </div>
                  <div class="work-title">{{ $w->title }}</div>
                  <div class="deadline">
                    <span><i class='bx bx-calendar'></i></span>
                    <span>Deadline: {{ $hours }} jam lagi</span>
                  </div>
                </div>
                @if(strtoupper($w->type) === 'KUIS')
                  <a class="btn-do" href="{{ route('siswa.kuis.start', [$w->teaching_id, $w->id]) }}">Kerjakan</a>
                @else
                  <a class="btn-do" href="{{ route('siswa.tugas.submit.create', [$w->teaching_id, $w->id]) }}">Kerjakan</a>
                @endif
              </div>
            @empty
              <div class="ann-text">Belum ada tugas atau kuis mendatang.</div>
            @endforelse
          </div>
        </section>

        <aside class="panel">
          <h2><i class='bx bx-bell'></i> Pengumuman Terbaru</h2>
          <div class="subtitle">Update dan notifikasi penting</div>

          <div class="ann-list">
            @forelse($latestAnnouncements as $a)
              <div class="ann-item">
                <div class="ann-title">{{ $a->title }}</div>
                <div class="ann-text">{!! nl2br(e($a->content)) !!}</div>
                <div class="ann-meta">
                  <span class="ann-chip">{{ $a->schoolClass->name ?? 'Umum' }}</span>
                  <span style="color:#6b7280; font-size:12px;">{{ optional($a->created_at)->format('j M') }}</span>
                </div>
              </div>
            @empty
              <div class="ann-text">Belum ada pengumuman terbaru.</div>
            @endforelse
          </div>
        </aside>
      </div>

      <section class="panel" style="margin-top:20px;">
        <h2><i class='bx bx-line-chart'></i> Progres Belajar Bulan Ini</h2>
        <div class="subtitle">{{ $monthlyDone }} dari {{ $monthlyTotal }} tugas telah selesai</div>

        <div style="margin:16px 0 8px; font-weight:700;">Penyelesaian Tugas</div>
        <div style="position:relative; height:10px; background:#E5E7EB; border-radius:999px; overflow:hidden;">
          <div style="position:absolute; left:0; top:0; bottom:0; width: {{ $monthlyPercent }}%; background:#111827;"></div>
        </div>
        <div style="display:flex; justify-content:flex-end; color:#111827; font-weight:700; margin-top:6px;">{{ $monthlyPercent }}%</div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:14px;">
          <div>
            <div style="color:#6b7280;">Selesai</div>
            <div style="color:#059669; font-weight:800;">{{ $monthlyDone }} tugas</div>
          </div>
          <div>
            <div style="color:#6b7280;">Tersisa</div>
            <div style="color:#EA580C; font-weight:800;">{{ $monthlyRemaining }} tugas</div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>