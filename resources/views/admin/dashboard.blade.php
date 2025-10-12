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
            padding: 32px;
            margin-left: 300px;
            /* Jarak seukuran lebar sidebar */
        }

        .main-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .main-header p {
            color: var(--secondary-text-color);
            margin-bottom: 32px;
        }

        /* --- Kartu Statistik --- */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background-color: var(--card-bg-color);
            padding: 24px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .stat-card .icon-container {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .stat-card:nth-child(1) .icon-container {
            background-color: #E0E7FF;
            color: #4338CA;
        }

        /* Total Pengguna */
        .stat-card:nth-child(2) .icon-container {
            background-color: #FEF3C7;
            color: #D97706;
        }

        /* Jumlah Guru */
        .stat-card:nth-child(3) .icon-container {
            background-color: #D1FAE5;
            color: #059669;
        }

        /* Jumlah Siswa */
        .stat-card:nth-child(4) .icon-container {
            background-color: #F3E8FF;
            color: #8B5CF6;
        }

        /* Kelas Aktif */
        .stat-card .stat-info p {
            font-size: 14px;
            color: var(--secondary-text-color);
            margin-bottom: 4px;
        }

        .stat-card .stat-info h3 {
            font-size: 24px;
            font-weight: 700;
        }

        /* ... CSS lainnya dari layout Anda ... */
    </style>
</head>

<body>

    {{-- Memanggil komponen sidebar admin --}}
    @include('partials._sidebar-admin')

    <main class="main-content">
        <header class="main-header">
            {{-- Mengambil nama admin yang sedang login --}}
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p>Berikut adalah ringkasan sistem LMS hari ini</p>
        </header>

        <section class="stats-cards">
            <div class="stat-card">
                <div class="icon-container"><i class='bx bxs-group'></i></div>
                <div class="stat-info">
                    <p>Total Pengguna</p>
                    {{-- Tampilkan data dari controller --}}
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-container"><i class='bx bxs-user-account'></i></div>
                <div class="stat-info">
                    <p>Jumlah Guru</p>
                    {{-- Tampilkan data dari controller --}}
                    <h3>{{ $totalGuru }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-container"><i class='bx bxs-graduation'></i></div>
                <div class="stat-info">
                    <p>Jumlah Siswa</p>
                    {{-- Tampilkan data dari controller --}}
                    <h3>{{ $totalSiswa }}</h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-container"><i class='bx bx-archive'></i></div>
                <div class="stat-info">
                    <p>Kelas Aktif</p>
                    {{-- Ganti 45 dengan variabel jika sudah ada, misal: $totalKelas --}}
                    <h3>45</h3>
                </div>
            </div>
        </section>
        {{-- Anda bisa menambahkan section lain yang relevan untuk admin di sini --}}
        {{-- Contoh: Grafik pengguna baru, log aktivitas terakhir, dll. --}}

    </main>
</body>

</html>