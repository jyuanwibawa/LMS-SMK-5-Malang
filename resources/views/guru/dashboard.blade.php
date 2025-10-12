<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 40px;
        }

        h1 {
            color: #1a202c;
        }

        p {
            color: #4a5568;
        }

        .logout-form button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #e53e3e;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout-form button:hover {
            background-color: #c53030;
        }
    </style>
</head>

<body>

    <h1>Dashboard Guru</h1>
    <p>Selamat datang, {{ auth()->user()->name }}. Anda login sebagai Guru.</p>

    <form class="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Keluar</button>
    </form>

</body>

</html>