<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Pelajaran</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --card-bg:#FFFFFF; --text-primary:#1A202C; --text-secondary:#718096; --border-color:#E2E8F0; --dark:#121212; --bg:#f8f9fa; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text-primary); }
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

        .main-content { margin-left:300px; padding:2rem; }
        .container { max-width:1200px; margin:0 auto; }
        header h1 { font-size:1.8rem; font-weight:700; margin-bottom:0.25rem; }
        .subtitle { color:var(--text-secondary); }

        .card { background:#fff; border:1px solid var(--border-color); border-radius:16px; padding:1.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; }
        .card-title h2 { font-size:1.25rem; font-weight:600; margin-bottom:0.25rem; }
        .card-title p { color:var(--text-secondary); font-size:0.95rem; }
        .add-button { background:#121212; color:#fff; border:none; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:0.5rem; }
        .add-button:hover { background:#343a40; }

        .table-container { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:0.9rem 1rem; text-align:left; border-bottom:1px solid var(--border-color); font-size:0.92rem; }
        thead th { color:var(--text-secondary); font-weight:600; }
        .actions { display:flex; gap:0.5rem; align-items:center; }
        .icon-button { background:none; border:none; color:#718096; display:flex; align-items:center; gap:6px; cursor:pointer; }
        .icon-button:hover { color:#1A202C; }
        .icon-button.delete:hover { color:#DC2626; }

        .modal-overlay { position:fixed; inset:0; background-color:rgba(33,37,41,0.5); z-index:1000; }
        .modal { position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; width:90%; max-width:520px; border-radius:16px; border:1px solid var(--border-color); overflow:hidden; z-index:1001; }
        .modal-header { display:flex; justify-content:space-between; align-items:flex-start; padding:1.25rem 1.5rem; border-bottom:1px solid var(--border-color); }
        .modal-body { padding:1.25rem 1.5rem; }
        .modal-footer { display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.5rem; border-top:1px solid var(--border-color); background:#f8f9fa; }
        .close-button { font-size:2rem; font-weight:300; line-height:1; background:none; border:none; color:#718096; cursor:pointer; }
        .form-group { margin-bottom:1rem; }
        .form-group label { display:block; font-weight:600; margin-bottom:0.5rem; }
        .form-group input, .form-group select { width:100%; padding:0.75rem; border:1px solid var(--border-color); border-radius:10px; }
        .button-secondary { background:#e9ecef; color:#1A202C; border:1px solid #dee2e6; padding:0.7rem 1.2rem; border-radius:10px; font-weight:600; cursor:pointer; }
        .button-primary { background:#121212; color:#fff; border:none; padding:0.7rem 1.2rem; border-radius:10px; font-weight:600; cursor:pointer; }
        .hidden { display:none; }
    </style>
</head>
<body>
    @include('partials._sidebar-admin')

    <main class="main-content">
        <main class="container">
            <header>
                <button onclick="history.back()" class="icon-button" style="margin-bottom:8px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali</span>
                </button>
                <h1>Kelola Mata Pelajaran: {{ $course->name }}</h1>
                <p class="subtitle">Tambahkan guru dan kelas pengajar untuk mata pelajaran ini</p>
            </header>

            <div class="card" style="margin-top:16px">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Daftar Pengajaran</h2>
                        <p>Guru yang mengajar {{ $course->name }} di kelas-kelas</p>
                    </div>
                    <button class="add-button" id="openTeachingModalBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah Guru</span>
                    </button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->teachings as $teach)
                            <tr>
                                <td>{{ $teach->user->name }}</td>
                                <td>{{ $teach->schoolClass->name }} ({{ $teach->schoolClass->academic_year }})</td>
                                <td class="actions">
                                    <form method="POST" action="{{ route('admin.courses.teachings.destroy', [$course, $teach]) }}" onsubmit="return confirm('Hapus pengajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-button delete" aria-label="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3">Belum ada pengajaran untuk mata pelajaran ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </main>

    <div id="modalOverlay" class="modal-overlay hidden"></div>

    <div id="modalTambahTeaching" class="modal hidden">
        <div class="modal-header">
            <div>
                <h2>Tambah Guru untuk {{ $course->name }}</h2>
                <p>Pilih guru dan kelas</p>
            </div>
            <button class="close-button" id="closeTeachingModalBtn">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.courses.teachings.store', $course) }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="teacherSelect">Guru</label>
                    <select id="teacherSelect" name="user_id" required>
                        <option value="" disabled selected>— Pilih guru —</option>
                        @foreach($availableTeachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} • {{ $t->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="classSelect">Kelas</label>
                    <select id="classSelect" name="class_id" required>
                        <option value="" disabled selected>— Pilih kelas —</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} • {{ $c->academic_year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-secondary" id="batalTeachingBtn">Batal</button>
                <button type="submit" class="button-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        const modalOverlay = document.getElementById('modalOverlay');
        const modalTeaching = document.getElementById('modalTambahTeaching');
        const openTeachingModalBtn = document.getElementById('openTeachingModalBtn');
        const closeTeachingModalBtn = document.getElementById('closeTeachingModalBtn');
        const batalTeachingBtn = document.getElementById('batalTeachingBtn');
        const openModal = (m)=>{ m.classList.remove('hidden'); modalOverlay.classList.remove('hidden'); };
        const closeModal = (m)=>{ m.classList.add('hidden'); modalOverlay.classList.add('hidden'); };
        if (openTeachingModalBtn) openTeachingModalBtn.addEventListener('click', ()=> openModal(modalTeaching));
        if (closeTeachingModalBtn) closeTeachingModalBtn.addEventListener('click', ()=> closeModal(modalTeaching));
        if (batalTeachingBtn) batalTeachingBtn.addEventListener('click', ()=> closeModal(modalTeaching));
        if (modalOverlay) modalOverlay.addEventListener('click', ()=> closeModal(modalTeaching));
    </script>
</body>
</html>
