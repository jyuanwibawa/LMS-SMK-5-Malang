<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { font-family:'Inter', sans-serif; background:#F7F8FC; margin:0; }
        .main-content { margin-left:280px; padding:32px 40px; box-sizing:border-box; }
        .main-header h2 { font-size:26px; font-weight:800; margin:0 0 4px; color:#111827; }
        .main-header p { margin:0; color:#6B7280; }

        .stats-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-top:24px; }
        .stat-card { background:#fff; border-radius:18px; border:1px solid #E5E7EB; padding:16px 18px; display:flex; flex-direction:column; gap:10px; }
        .stat-header { display:flex; align-items:center; justify-content:space-between; }
        .stat-label { font-size:14px; color:#6B7280; font-weight:600; }
        .stat-value { font-size:24px; font-weight:800; color:#111827; }
        .stat-icon { width:40px; height:40px; border-radius:14px; display:flex; align-items:center; justify-content:center; }
        .icon-blue { background:#EEF2FF; color:#2563EB; }
        .icon-green { background:#ECFDF5; color:#16A34A; }
        .icon-amber { background:#FFFBEB; color:#D97706; }
        .icon-purple { background:#F3E8FF; color:#7C3AED; }

        .bottom-grid { margin-top:28px; display:grid; grid-template-columns:2fr 1.1fr; gap:20px; align-items:flex-start; }
        .panel { background:#fff; border-radius:18px; border:1px solid #E5E7EB; padding:18px 20px; }
        .panel-title { font-size:16px; font-weight:700; margin:0 0 4px; display:flex; align-items:center; gap:8px; }
        .panel-sub { font-size:13px; color:#6B7280; margin:0 0 14px; }

        .deadline-list { display:flex; flex-direction:column; gap:12px; }
        .deadline-item { border-radius:16px; border:1px solid #E5E7EB; padding:14px 16px; background:#FDFDFD; }
        .deadline-top { display:flex; align-items:center; gap:10px; margin-bottom:6px; }
        .badge-kind { font-size:11px; font-weight:700; padding:4px 10px; border-radius:999px; background:#111827; color:#fff; }
        .badge-course { font-size:12px; color:#4B5563; }
        .deadline-title { font-size:15px; font-weight:700; color:#111827; margin-bottom:6px; }
        .deadline-meta { display:flex; align-items:center; gap:6px; font-size:13px; color:#6B7280; }

        .activity-list { display:flex; flex-direction:column; gap:10px; }
        .activity-item { display:flex; align-items:flex-start; gap:10px; }
        .activity-avatar { width:32px; height:32px; border-radius:999px; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
        .activity-main { font-size:13px; }
        .activity-main strong { color:#111827; }
        .activity-desc { color:#4B5563; margin-bottom:2px; }
        .activity-time { font-size:12px; color:#9CA3AF; }
    </style>
</head>
<body>
    @include('partials._sidebar-guru')

    <main class="main-content">
        <header class="main-header">
            <h2>Dashboard Guru</h2>
            <p>Selamat datang kembali, {{ auth()->user()->name }}. Berikut ringkasan aktivitas kelas Anda.</p>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Total Kelas</div>
                    <div class="stat-icon icon-blue"><i class='bx bx-book-open'></i></div>
                </div>
                <div class="stat-value">{{ $totalClasses }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Total Siswa</div>
                    <div class="stat-icon icon-green"><i class='bx bx-user'></i></div>
                </div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Tugas Aktif</div>
                    <div class="stat-icon icon-amber"><i class='bx bx-task'></i></div>
                </div>
                <div class="stat-value">{{ $activeTasks }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-label">Menunggu Penilaian</div>
                    <div class="stat-icon icon-purple"><i class='bx bx-time-five'></i></div>
                </div>
                <div class="stat-value">{{ $pendingGrading }}</div>
            </div>
        </section>

        <section class="bottom-grid">
            <div class="panel">
                <h3 class="panel-title"><i class='bx bx-time-five'></i> Deadline Terdekat</h3>
                <p class="panel-sub">Tugas dan kuis yang akan berakhir</p>

                <div class="deadline-list">
                    @forelse($upcomingDeadlines as $work)
                        <article class="deadline-item">
                            <div class="deadline-top">
                                <span class="badge-kind">{{ strtoupper($work->type ?? 'TUGAS') }}</span>
                                <span class="badge-course">{{ $work->teaching->course->name ?? 'Mata pelajaran' }} {{ $work->teaching->schoolClass->name ?? '' }}</span>
                            </div>
                            <div class="deadline-title">{{ $work->title }}</div>
                            <div class="deadline-meta">
                                <i class='bx bx-calendar'></i>
                                <span>{{ optional($work->end_time)->translatedFormat('d F \p\u\k\u\l H.i') }}</span>
                            </div>
                        </article>
                    @empty
                        <p class="panel-sub">Belum ada deadline tugas atau kuis dalam waktu dekat.</p>
                    @endforelse
                </div>
            </div>

            <aside class="panel">
                <h3 class="panel-title"><i class='bx bx-bell'></i> Aktivitas Terkini</h3>
                <p class="panel-sub">Update dari siswa</p>

                <div class="activity-list">
                    @forelse($recentActivities as $act)
                        @php
                            $u = $act->user;
                            $initials = $u ? strtoupper(mb_substr($u->name, 0, 2)) : 'US';
                        @endphp
                        <div class="activity-item">
                            <div class="activity-avatar">{{ $initials }}</div>
                            <div class="activity-main">
                                <div class="activity-desc">
                                    <strong>{{ $u->name ?? 'Siswa' }}</strong>
                                    <span> {{ $act->description }}</span>
                                </div>
                                <div class="activity-time">{{ optional($act->timestamp)->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="panel-sub">Belum ada aktivitas terbaru dari siswa.</p>
                    @endforelse
                </div>
            </aside>
        </section>

        <section class="bottom-grid" style="margin-top:24px; grid-template-columns:2.1fr 0.9fr;">
            <div class="panel">
                <h3 class="panel-title"><i class='bx bx-upload'></i> Pengumpulan Terbaru</h3>
                <p class="panel-sub">Status pengumpulan tugas siswa</p>

                <div class="deadline-list">
                    @forelse($latestProgress as $row)
                        @php
                            $a = $row['assignment'];
                            $t = $a->teaching;
                        @endphp
                        <article class="deadline-item" style="border-radius:20px;">
                            <div class="deadline-title" style="margin-bottom:2px;">{{ $a->title }}</div>
                            <div class="badge-course" style="margin-bottom:8px;">
                                {{ $t->course->name ?? 'Mata pelajaran' }} {{ $t->schoolClass->name ?? '' }}
                            </div>
                            <div class="deadline-meta" style="justify-content:space-between; margin-bottom:6px;">
                                <span style="font-size:13px; color:#6B7280;">Progress</span>
                                <span style="font-size:12px; font-weight:700; padding:4px 10px; border-radius:999px; background:#F3F4F6;">{{ $row['submitted'] }}/{{ $row['classSize'] }}</span>
                            </div>
                            <div style="height:6px; border-radius:999px; background:#E5E7EB; overflow:hidden;">
                                <div style="height:100%; width:{{ $row['percent'] ?? 0 }}%; background:#111827;"></div>
                            </div>
                            <div style="margin-top:6px; font-size:12px; color:#6B7280;">{{ $row['percent'] !== null ? $row['percent'] . '%' : '-' }}</div>
                        </article>
                    @empty
                        <p class="panel-sub">Belum ada pengumpulan tugas dari siswa.</p>
                    @endforelse
                </div>
            </div>

            <aside class="panel">
                <h3 class="panel-title"><i class='bx bx-bolt-circle'></i> Aksi Cepat</h3>
                <p class="panel-sub">Kelola materi dan penugasan dengan cepat</p>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="{{ route('guru.kelas.index') }}" style="text-decoration:none;">
                        <div style="display:flex; align-items:center; gap:10px; border-radius:999px; border:1px solid #E5E7EB; padding:8px 12px; color:#111827; font-size:14px; font-weight:600;">
                            <i class='bx bx-upload'></i>
                            <span>Upload Materi</span>
                        </div>
                    </a>
                    <a href="{{ route('guru.kelas.index') }}" style="text-decoration:none;">
                        <div style="display:flex; align-items:center; gap:10px; border-radius:999px; border:1px solid #E5E7EB; padding:8px 12px; color:#111827; font-size:14px; font-weight:600;">
                            <i class='bx bx-file'></i>
                            <span>Buat Tugas</span>
                        </div>
                    </a>
                    <a href="{{ route('guru.kelas.index') }}" style="text-decoration:none;">
                        <div style="display:flex; align-items:center; gap:10px; border-radius:999px; border:1px solid #E5E7EB; padding:8px 12px; color:#111827; font-size:14px; font-weight:600;">
                            <i class='bx bx-help-circle'></i>
                            <span>Buat Kuis</span>
                        </div>
                    </a>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
