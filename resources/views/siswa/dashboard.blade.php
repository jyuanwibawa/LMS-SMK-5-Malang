<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>

    {{-- Mengimpor font dan ikon yang dibutuhkan oleh sidebar --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS untuk layout utama --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F7F8FC;
            margin: 0;
            padding: 0;
        }

        .main-content {
            /* Memberi jarak kiri seukuran lebar sidebar */
            margin-left: 260px;
            padding: 40px;
        }

        .main-content h1 {
            color: #1a202c;
        }

        .main-content p {
            color: #4a5568;
        }
    </style>
</head>

<body>

    {{-- Memasukkan komponen sidebar --}}
    @include('partials._sidebar-siswa')

    {{-- Konten Utama Halaman --}}
    <main class="main-content">
        <h1>Dashboard Siswa</h1>
        <p>Selamat datang, {{ auth()->user()->name }}.</p>
        <p>Ini adalah halaman utama Anda.</p>
    </main>

</body>

</html>