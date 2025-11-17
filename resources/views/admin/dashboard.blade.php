<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - LMS Portal</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- Variabel & Reset CSS --- */
        :root {
            --bg-color: #F7F8FC;
            --card-bg-color: #FFFFFF;
            --primary-text-color: #1A202C;
            --secondary-text-color: #718096;
            --border-color: #E2E8F0;
            --primary-color: #4A5568;
            --dark-color: #121212;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-text-color);
        }

        /* --- Sidebar --- */
        /*
            CATATAN: CSS untuk sidebar sebaiknya dipindah ke file CSS terpusat
            agar tidak duplikat di setiap halaman. Tapi untuk sementara, kita letakkan di sini.
        */
        .sidebar {
            width: 300px;
            height: 100vh;
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            left: 0;
            top: 0;
        }

        .admin-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-profile .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: var(--dark-color);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .admin-profile h4 {
            font-size: 20px;
            font-weight: 600;
        }

        .admin-profile p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .admin-profile .tag {
            background-color: #FEF3C7;
            color: #D97706;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .navigation {
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }

        .navigation ul {
            list-style: none;
        }

        .navigation ul li a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            margin-bottom: 10px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
        }

        .navigation ul li a i {
            font-size: 24px;
            margin-right: 16px;
        }

        .navigation ul li a:hover {
            background-color: #F1F5F9;
            color: var(--text-primary);
        }

        .navigation ul li a.active {
            background-color: var(--dark-color);
            color: white;
        }

        .logout-section {
            border-top: 1px solid var(--border-color);
            padding-top: 12px;
        }

        .logout-section a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            text-decoration: none;
            color: #EF4444;
            font-weight: 600;
            font-size: 16px;
            border-radius: 12px;
        }

        .logout-section a:hover {
            background-color: #FEF2F2;
        }

        .logout-section a i {
            font-size: 24px;
            margin-right: 16px;
            transform: scaleX(-1);
        }

        /* --- Konten Utama --- */
        .main-content {
            padding: 32px 40px;
            margin-left: 300px;
        }

        .main-header h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .main-header p {
            color: var(--secondary-text-color);
            margin-bottom: 24px;
        }

        /* --- Kartu Statistik --- */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .stat-card {
            background-color: #FFFFFF;
            padding: 18px 18px 16px;
            border-radius: 18px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stat-header {
            display:flex;
            align-items:center;
            gap:10px;
        }

        .icon-container {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .icon-blue { background:#EEF2FF; color:#2563EB; }
        .icon-green { background:#ECFDF5; color:#16A34A; }
        .icon-purple { background:#F3E8FF; color:#7C3AED; }
        .icon-orange { background:#FFFBEB; color:#EA580C; }

        .stat-title {
            font-size: 14px;
            color: #4B5563;
            font-weight:600;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color:#111827;
        }

        .stat-subtext {
            font-size: 13px;
            color:#6B7280;
        }

        @media (max-width: 1024px) {
            .stats-cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .stats-cards { grid-template-columns: 1fr; }
        }

        /* Kartu grafik bawah */
        .charts-grid {
            margin-top: 28px;
            display:grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap:18px;
        }
        .chart-card {
            background:#fff;
            border-radius:18px;
            border:1px solid var(--border-color);
            padding:18px 20px 20px;
        }
        .chart-card h3 {
            font-size:16px;
            font-weight:700;
            margin-bottom:4px;
        }
        .chart-card p {
            font-size:13px;
            color:#6B7280;
            margin-bottom:10px;
        }
        .chart-wrapper { width:100%; height:220px; }
        .chart-svg { width:100%; height:100%; }

        @media (max-width: 1024px) {
            .charts-grid { grid-template-columns:1fr; }
        }

        /* ... CSS lainnya dari layout Anda ... */
    </style>
</head>

<body>

    {{-- Memanggil komponen sidebar admin --}}
    @include('partials._sidebar-admin')

    <main class="main-content">
        <header class="main-header">
            <h2>Dashboard Admin</h2>
            <p>Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan sistem LMS.</p>
        </header>

        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="icon-container icon-blue"><i class='bx bx-user'></i></div>
                    <div class="stat-title">Total Siswa</div>
                </div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-subtext">&nbsp;</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="icon-container icon-green"><i class='bx bx-chalkboard'></i></div>
                    <div class="stat-title">Total Guru</div>
                </div>
                <div class="stat-value">{{ $totalGuru }}</div>
                <div class="stat-subtext">&nbsp;</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="icon-container icon-purple"><i class='bx bx-book-open'></i></div>
                    <div class="stat-title">Kelas Aktif</div>
                </div>
                <div class="stat-value">{{ $activeClasses }}</div>
                <div class="stat-subtext">&nbsp;</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="icon-container icon-orange"><i class='bx bx-user-plus'></i></div>
                    <div class="stat-title">Pengguna Baru</div>
                </div>
                <div class="stat-value">{{ $newUsersThisMonth }}</div>
                <div class="stat-subtext">bulan ini</div>
            </div>
        </section>

        <section class="charts-grid">
            <div class="chart-card">
                <h3>Aktivitas Pengguna Mingguan</h3>
                <p>Login harian siswa dan guru dalam 7 hari terakhir</p>
                <div class="chart-wrapper">
                    <svg class="chart-svg" viewBox="0 0 300 220" preserveAspectRatio="none">
                        <!-- grid -->
                        <g stroke="#E5E7EB" stroke-width="1">
                            <line x1="40" y1="30" x2="40" y2="190" />
                            <line x1="40" y1="190" x2="270" y2="190" />
                            <line x1="40" y1="70" x2="270" y2="70" stroke-dasharray="4 4" />
                            <line x1="40" y1="110" x2="270" y2="110" stroke-dasharray="4 4" />
                            <line x1="40" y1="150" x2="270" y2="150" stroke-dasharray="4 4" />
                        </g>
                        <!-- siswa line (hitam) -->
                        <polyline fill="none" stroke="#111827" stroke-width="2.5"
                            points="{{ $weeklySiswaPointsStr }}" />
                        <!-- guru line (abu) -->
                        <polyline fill="none" stroke="#9CA3AF" stroke-width="2"
                            points="{{ $weeklyGuruPointsStr }}" />
                        <!-- legend -->
                        <circle cx="120" cy="205" r="4" fill="none" stroke="#9CA3AF" stroke-width="2" />
                        <text x="130" y="208" font-size="11" fill="#4B5563">Guru</text>
                        <line x1="185" y1="205" x2="197" y2="205" stroke="#111827" stroke-width="2.5" />
                        <text x="202" y="208" font-size="11" fill="#111827">Siswa</text>
                    </svg>
                </div>
            </div>

            <div class="chart-card">
                <h3>Registrasi Bulanan</h3>
                <p>Pengguna baru yang terdaftar 6 bulan terakhir</p>
                <div class="chart-wrapper">
                    <svg class="chart-svg" viewBox="0 0 300 220" preserveAspectRatio="none">
                        <!-- axis -->
                        <g stroke="#E5E7EB" stroke-width="1">
                            <line x1="40" y1="30" x2="40" y2="190" />
                            <line x1="40" y1="190" x2="270" y2="190" />
                        </g>
                        @php
                            $xStart = 55; $slot = 40; $siswaWidth = 14; $guruWidth = 10;
                            $baseY = 190; $maxH = 110;
                            $months = $monthLabels;
                        @endphp
                        <!-- bars siswa (hitam) -->
                        <g fill="#111827">
                            @foreach($monthlySiswaCounts as $i => $count)
                                @php($h = $maxMonthly > 0 ? max(2, ($count / $maxMonthly) * $maxH) : 2)
                                @php($x = $xStart + $i * $slot)
                                <rect x="{{ $x }}" y="{{ $baseY - $h }}" width="{{ $siswaWidth }}" height="{{ $h }}" rx="3" />
                            @endforeach
                        </g>
                        <!-- bars guru (abu) -->
                        <g fill="#9CA3AF">
                            @foreach($monthlyGuruCounts as $i => $count)
                                @php($h = $maxMonthly > 0 ? max(2, ($count / $maxMonthly) * ($maxH * 0.4)) : 2)
                                @php($x = $xStart - 13 + $i * $slot)
                                <rect x="{{ $x }}" y="{{ $baseY - $h }}" width="{{ $guruWidth }}" height="{{ $h }}" rx="2" />
                            @endforeach
                        </g>
                        <!-- month labels -->
                        <g font-size="11" fill="#4B5563">
                            @foreach($months as $i => $label)
                                @php($x = $xStart - 3 + $i * $slot)
                                <text x="{{ $x }}" y="205">{{ $label }}</text>
                            @endforeach
                        </g>
                        <!-- legend -->
                        <rect x="80" y="32" width="10" height="10" fill="#9CA3AF" rx="2" />
                        <text x="94" y="40" font-size="11" fill="#4B5563">Guru</text>
                        <rect x="150" y="32" width="10" height="10" fill="#111827" rx="2" />
                        <text x="164" y="40" font-size="11" fill="#111827">Siswa</text>
                    </svg>
                </div>
            </div>
        </section>

        <section style="margin-top:28px; display:grid; grid-template-columns: 2.1fr 1fr; gap:18px; align-items:flex-start;">
            <div style="background:#fff;border-radius:18px;border:1px solid #E5E7EB;padding:18px 20px;">
                <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">Akses Cepat</h3>
                <p style="font-size:13px;color:#6B7280;margin-bottom:16px;">Shortcut ke fitur-fitur utama</p>

                <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;">
                    <a href="{{ route('admin.users.index') }}" style="text-decoration:none;color:inherit;">
                        <div style="border:1px solid #E5E7EB;border-radius:14px;padding:14px 12px;height:100%;">
                            <div style="width:40px;height:40px;border-radius:12px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;margin-bottom:10px;"><i class='bx bx-user-plus' style="font-size:20px;"></i></div>
                            <div style="font-weight:700;margin-bottom:4px;">Tambah Pengguna</div>
                            <div style="font-size:13px;color:#6B7280;margin-bottom:10px;">Daftarkan guru atau siswa baru</div>
                            <div style="font-size:13px;font-weight:600;">Akses →</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.academic.index') }}" style="text-decoration:none;color:inherit;">
                        <div style="border:1px solid #E5E7EB;border-radius:14px;padding:14px 12px;height:100%;">
                            <div style="width:40px;height:40px;border-radius:12px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;margin-bottom:10px;"><i class='bx bx-book-open' style="font-size:20px;"></i></div>
                            <div style="font-weight:700;margin-bottom:4px;">Kelola Kelas</div>
                            <div style="font-size:13px;color:#6B7280;margin-bottom:10px;">Atur kelas dan mata pelajaran</div>
                            <div style="font-size:13px;font-weight:600;">Akses →</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.logs.index') }}" style="text-decoration:none;color:inherit;">
                        <div style="border:1px solid #E5E7EB;border-radius:14px;padding:14px 12px;height:100%;">
                            <div style="width:40px;height:40px;border-radius:12px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;margin-bottom:10px;"><i class='bx bx-line-chart' style="font-size:20px;"></i></div>
                            <div style="font-weight:700;margin-bottom:4px;">Lihat Aktivitas</div>
                            <div style="font-size:13px;color:#6B7280;margin-bottom:10px;">Monitor log sistem</div>
                            <div style="font-size:13px;font-weight:600;">Akses →</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Aktivitas Terkini --}}
            <aside style="background:#fff;border-radius:18px;border:1px solid #E5E7EB;padding:18px 20px;">
                <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">Aktivitas Terkini</h3>
                <p style="font-size:13px;color:#6B7280;margin-bottom:12px;">Update sistem real-time</p>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    @forelse($recentActivities as $act)
                        <div style="display:flex;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:999px;background:{{ optional(optional($act->user)->role)->name === 'guru' ? '#ECFDF5' : (optional(optional($act->user)->role)->name === 'admin' ? '#FEF3C7' : '#EEF2FF') }};display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                                @if(optional(optional($act->user)->role)->name === 'guru')
                                    <i class='bx bx-chalkboard' style="font-size:18px;"></i>
                                @elseif(optional(optional($act->user)->role)->name === 'admin')
                                    <i class='bx bx-shield-quarter' style="font-size:18px;"></i>
                                @else
                                    <i class='bx bx-user' style="font-size:18px;"></i>
                                @endif
                            </div>
                            <div style="font-size:13px;">
                                <div style="font-weight:700;color:#111827;">
                                    {{ optional($act->user)->name ?? 'Pengguna' }}
                                </div>
                                <div style="color:#4B5563;margin-bottom:2px;">
                                    {{ $act->description }}
                                </div>
                                <div style="color:#9CA3AF;font-size:12px;">
                                    {{ optional($act->timestamp)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="font-size:13px;color:#6B7280;">Belum ada aktivitas terbaru.</div>
                    @endforelse
                </div>
            </aside>
        </section>

    </main>
</body>

</html>