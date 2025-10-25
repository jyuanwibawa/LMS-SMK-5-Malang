<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter', sans-serif; background:#f8f9fa; }
        .main-content { margin-left:280px; padding:32px; }
        h1 { color:#1a202c; }
        p { color:#4a5568; }
        .logout-form button { margin-top:16px; padding:10px 16px; font-size:14px; color:#fff; background:#e53e3e; border:none; border-radius:8px; cursor:pointer; }
        .logout-form button:hover { background:#c53030; }
    </style>
</head>
<body>
    @include('partials._sidebar-guru')

    <main class="main-content">
        <h1>Dashboard Guru</h1>
        <p>Selamat datang, {{ auth()->user()->name }}. Anda login sebagai Guru.</p>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </main>
</body>
</html>
