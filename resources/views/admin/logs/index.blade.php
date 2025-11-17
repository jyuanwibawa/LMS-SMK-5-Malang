<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --card-bg:#FFFFFF; --text-primary:#1A202C; --text-secondary:#718096; --border-color:#E2E8F0; --dark:#121212; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background:#f8f9fa; }
        .main-content { margin-left:300px; padding:2rem 2.5rem; }

        h1 { font-size: 2.2rem; font-weight:800; margin-bottom:4px; color:var(--text-primary); }
        .subtitle-page { color:var(--text-secondary); margin-bottom:20px; }

        .wrapper { max-width:1100px; }
        .card-shell { background:#fff; border-radius:18px; padding:20px 22px; border:1px solid #e5e7eb; }

        .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
        .card-header-left h2 { font-size:18px; font-weight:700; display:flex; align-items:center; gap:8px; }
        .card-header-left p { margin-top:4px; font-size:14px; color:var(--text-secondary); }

        .summary { font-size:13px; color:var(--text-secondary); margin-top:4px; }

        .toolbar { display:flex; gap:10px; align-items:center; }
        .search-input { flex:1; border-radius:999px; border:1px solid #E5E7EB; padding:10px 14px; font-size:14px; display:flex; align-items:center; gap:8px; background:#F9FAFB; }
        .search-input input { border:none; outline:none; width:100%; font-size:14px; background:transparent; }
        .search-input span { color:#9CA3AF; font-size:18px; }

        .filter-btn { display:inline-flex; align-items:center; gap:8px; border-radius:999px; border:1px solid #E5E7EB; padding:9px 14px; background:#fff; font-size:14px; font-weight:600; color:#374151; cursor:pointer; }

        .list { margin-top:14px; display:flex; flex-direction:column; gap:12px; }
        .item { display:flex; justify-content:space-between; align-items:flex-start; padding:14px 16px; border-radius:16px; border:1px solid #E5E7EB; background:#F9FAFB; }
        .item-left { display:flex; gap:12px; }
        .icon-circle { width:40px; height:40px; border-radius:12px; background:#E5E7EB; display:flex; align-items:center; justify-content:center; font-size:22px; }
        .item-main { display:flex; flex-direction:column; gap:4px; }
        .item-top-line { display:flex; align-items:center; gap:8px; }
        .actor-role { font-weight:700; color:#111827; }
        .chip-action { font-size:11px; padding:4px 10px; border-radius:999px; background:#DCFCE7; color:#166534; font-weight:700; }
        .chip-upload { background:#EDE9FE; color:#7C3AED; }
        .chip-task { background:#DBEAFE; color:#1D4ED8; }
        .chip-quiz { background:#FCE7F3; color:#BE185D; }
        .chip-student { background:#FEF3C7; color:#92400E; }

        .item-desc { font-size:14px; color:#111827; }
        .item-extra { font-size:13px; color:#6B7280; }

        .item-right { font-size:12px; color:#6B7280; white-space:nowrap; margin-left:18px; }

        .sidebar { width:300px; height:100vh; background-color:var(--card-bg); border-right:1px solid var(--border-color); padding:24px; display:flex; flex-direction:column; justify-content:space-between; position:fixed; left:0; top:0; }
        .admin-profile { text-align:center; margin-bottom:20px; }
        .admin-profile .avatar { width:64px; height:64px; border-radius:50%; background-color:var(--dark); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:600; font-size:24px; margin-bottom:12px; }
        .admin-profile h4 { font-size:20px; font-weight:600; }
        .admin-profile p { font-size:14px; color:var(--text-secondary); margin-bottom:16px; }
        .admin-profile .tag { background-color:#FEF3C7; color:#D97706; font-size:12px; font-weight:600; padding:6px 12px; border-radius:8px; display:inline-flex; align-items:center; gap:6px; }
        .navigation { border-top:1px solid var(--border-color); padding-top:20px; }
        .navigation ul { list-style:none; }
        .navigation ul li a { display:flex; align-items:center; padding:14px 20px; margin-bottom:10px; border-radius:12px; text-decoration:none; color:var(--text-secondary); font-weight:600; font-size:16px; transition:all .2s; }
        .navigation ul li a i { font-size:24px; margin-right:16px; }
        .navigation ul li a:hover { background-color:#F1F5F9; color:var(--text-primary); }
        .navigation ul li a.active { background-color:var(--dark); color:#fff; }
        .logout-section { border-top:1px solid var(--border-color); padding-top:12px; }
        .logout-section a { display:flex; align-items:center; padding:14px 20px; text-decoration:none; color:#EF4444; font-weight:600; font-size:16px; border-radius:12px; }
        .logout-section a:hover { background-color:#FEF2F2; }
        .logout-section a i { font-size:24px; margin-right:16px; transform:scaleX(-1); }
    </style>
</head>
<body>
    @include('partials._sidebar-admin')

    <main class="main-content">
        <div class="wrapper">
            <h1>Log Aktivitas Sistem</h1>
            <p class="subtitle-page">Monitoring semua aktivitas yang terjadi di dalam sistem LMS.</p>

            <section class="card-shell">
                <div class="card-header">
                    <div class="card-header-left">
                        <h2><i class='bx bx-history'></i> Riwayat Aktivitas</h2>
                        <p>Total {{ $logs->count() }} aktivitas tercatat</p>
                    </div>
                    <div class="toolbar">
                        <div class="search-input">
                            <span><i class='bx bx-search'></i></span>
                            <input type="text" placeholder="Cari aktivitas..." disabled>
                        </div>
                        <button class="filter-btn" type="button">
                            <span><i class='bx bx-slider-alt'></i></span>
                            <span>Semua</span>
                        </button>
                    </div>
                </div>

                <div class="list">
                    @forelse($logs as $log)
                        @php
                            $user = $log->user;
                            $role = optional($user->role)->name;
                            $icon = 'default';
                            $chipLabel = 'Aktivitas';
                            $chipClass = 'chip-action';
                            $extra = '';

                            switch ($log->activity_type) {
                                case 'guru_upload_materi':
                                    $icon = 'upload';
                                    $chipLabel = 'Upload';
                                    $chipClass = 'chip-upload';
                                    break;
                                case 'guru_buat_tugas':
                                    $icon = 'task';
                                    $chipLabel = 'Tugas';
                                    $chipClass = 'chip-task';
                                    break;
                                case 'guru_buat_kuis':
                                    $icon = 'quiz';
                                    $chipLabel = 'Kuis';
                                    $chipClass = 'chip-quiz';
                                    break;
                                case 'siswa_submit_tugas':
                                    $icon = 'submit-task';
                                    $chipLabel = 'Submit Tugas';
                                    $chipClass = 'chip-student';
                                    break;
                                case 'siswa_submit_kuis':
                                    $icon = 'submit-quiz';
                                    $chipLabel = 'Submit Kuis';
                                    $chipClass = 'chip-student';
                                    break;
                            }

                            if ($role === 'siswa') {
                                $extra = $user?->name . ' (Siswa)';
                            } elseif ($role === 'guru') {
                                $extra = $user?->name . ' (Guru)';
                            } elseif ($role === 'admin') {
                                $extra = $user?->name . ' (Admin)';
                            }
                        @endphp
                        <article class="item">
                            <div class="item-left">
                                <div class="icon-circle">
                                    @switch($icon)
                                        @case('upload')
                                            <i class='bx bx-upload'></i>
                                            @break
                                        @case('task')
                                            <i class='bx bx-task'></i>
                                            @break
                                        @case('quiz')
                                            <i class='bx bx-question-mark'></i>
                                            @break
                                        @case('submit-task')
                                            <i class='bx bx-upload'></i>
                                            @break
                                        @case('submit-quiz')
                                            <i class='bx bx-check-circle'></i>
                                            @break
                                        @default
                                            <i class='bx bx-user'></i>
                                    @endswitch
                                </div>
                                <div class="item-main">
                                    <div class="item-top-line">
                                        <span class="actor-role">{{ $role ? ucfirst($role) : 'Pengguna' }}</span>
                                        <span class="chip-action {{ $chipClass }}">{{ $chipLabel }}</span>
                                    </div>
                                    <div class="item-desc">{{ $log->description }}</div>
                                    @if($extra)
                                        <div class="item-extra">{{ $extra }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="item-right">
                                {{ optional($log->timestamp)->format('d M Y, H.i') }}
                            </div>
                        </article>
                    @empty
                        <div class="item" style="justify-content:center;">
                            <div class="item-extra">Belum ada aktivitas yang tercatat.</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</body>
</html>
