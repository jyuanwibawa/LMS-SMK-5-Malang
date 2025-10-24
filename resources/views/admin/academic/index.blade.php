<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akademik</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --card-bg:#FFFFFF; --text-primary:#1A202C; --text-secondary:#718096; --border-color:#E2E8F0; --dark:#121212; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background:#f8f9fa; }
        .main-content { margin-left:300px; padding:2rem; }
        .card { background:#fff; border-radius:12px; padding:24px; border:1px solid #e5e7eb; }
        h1 { font-size: 2rem; font-weight:700; margin-bottom:12px; }
        p { color:#6b7280; }

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
        <div class="card">
            <h1>Manajemen Akademik</h1>
            <p>Halaman ini masih kosong. Konten akan ditambahkan nanti.</p>
        </div>
    </main>
</body>
</html>
