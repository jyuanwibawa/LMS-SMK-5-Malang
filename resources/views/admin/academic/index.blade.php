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

        /* --- Variabel & Reset Dasar tambahan untuk halaman akademik --- */
        :root {
            --bg-color: #f8f9fa;
            --card-inner-bg: #f8f9fa;
            --button-bg: #212529;
            --button-text: #ffffff;
            --accent-color-light: #e7f5ee;
            --accent-color-dark: #28a745;
            --danger-color: #dc3545;
        }

        /* --- Helper --- */
        .hidden { display: none; }

        /* --- Layout Utama --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        header h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 0.25rem; }
        .subtitle { color: var(--text-secondary); font-size: 1rem; }

        /* --- Komponen Tab --- */
        .tabs { margin: 2rem 0 1.5rem 0; background-color: #e9ecef; border-radius: 12px; padding: 5px; display: inline-block; }
        .tab-link { border: none; padding: 0.75rem 1.5rem; border-radius: 9px; cursor: pointer; font-size: 0.9rem; font-weight: 600; transition: all 0.2s ease-in-out; }
        .tab-link.active { background-color: var(--card-bg); color: var(--text-primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .tab-link:not(.active) { background-color: transparent; color: var(--text-secondary); }

        /* --- Card & Header --- */
        .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .card-title h2 { font-size:1.25rem; font-weight:600; margin-bottom:0.2rem; }
        .card-title p { color: var(--text-secondary); font-size:0.9rem; }
        .add-button { background-color: var(--button-bg); color: var(--button-text); border:none; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition: background-color 0.2s; }
        .add-button:hover { background-color:#343a40; }

        .icon-button { background:none; border:none; cursor:pointer; padding:0.25rem; color:var(--text-secondary); transition:color 0.2s; display:flex; align-items:center; }
        .icon-button svg { width:20px; height:20px; }
        .icon-button:hover { color: var(--text-primary); }
        .icon-button.delete:hover { color: var(--danger-color); }
        .icon-button span { margin-left:0.4rem; font-weight:500; font-size:0.9rem; color:var(--text-secondary); transition:color 0.2s; }
        .icon-button:hover span { color: var(--text-primary); }

        /* === Tabel Kelas === */
        .table-container { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:1rem; text-align:left; vertical-align:middle; font-size:0.9rem; }
        thead th { font-weight:600; color:var(--text-secondary); border-bottom:1px solid var(--border-color); }
        tbody tr { border-bottom:1px solid var(--border-color); }
        tbody tr:last-child { border-bottom:none; }
        tbody td { color:var(--text-primary); font-weight:500; }
        .status-badge { padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.8rem; font-weight:600; }
        .status-badge.active { background-color: var(--accent-color-light); color: var(--accent-color-dark); }
        .actions { display:flex; gap:0.75rem; align-items:center; }

        /* === Kartu Mapel === */
        .subject-list { display:flex; flex-direction:column; gap:1rem; }
        .subject-card { display:flex; align-items:center; gap:1.25rem; background-color:var(--card-inner-bg); padding:1.25rem; border-radius:12px; border:1px solid var(--border-color); }
        .subject-icon { background-color:var(--text-primary); color:var(--button-text); flex-shrink:0; width:64px; height:64px; display:flex; align-items:center; justify-content:center; border-radius:10px; }
        .subject-icon svg { width:32px; height:32px; }
        .subject-info { flex-grow:1; }
        .subject-info h3 { font-size:1.1rem; font-weight:600; margin-bottom:0.25rem; }
        .subject-info p { font-size:0.9rem; color:var(--text-secondary); margin-bottom:0.5rem; }
        .subject-info span { font-size:0.8rem; color:var(--text-secondary); font-weight:500; }

        /* === Modal === */
        .modal-overlay { position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(33,37,41,0.5); z-index:1000; }
        .modal { position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:#fff; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,0.15); z-index:1001; width:90%; max-width:500px; }
        .modal-header { display:flex; justify-content:space-between; align-items:flex-start; padding:1.5rem; border-bottom:1px solid var(--border-color); }
        .modal-header h2 { font-size:1.25rem; }
        .modal-header p { color:var(--text-secondary); font-size:0.9rem; margin-top:0.25rem; }
        .close-button { font-size:2rem; font-weight:300; line-height:1; color:var(--text-secondary); background:none; border:none; cursor:pointer; }
        .modal-body { padding:1.5rem; }
        .form-group { margin-bottom:1.25rem; }
        .form-group:last-child { margin-bottom:0; }
        .form-group label { display:block; font-weight:600; margin-bottom:0.5rem; font-size:0.9rem; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:0.75rem; border:1px solid var(--border-color); border-radius:8px; font-size:0.9rem; font-family:'Inter', sans-serif; transition:border-color 0.2s, box-shadow 0.2s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:#80bdff; outline:0; box-shadow:0 0 0 0.2rem rgba(0,123,255,.25); }
        .form-group textarea { resize:vertical; min-height:80px; }
        .form-row { display:flex; gap:1rem; }
        .form-row .form-group { flex:1; }
        .modal-footer { display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.5rem; border-top:1px solid var(--border-color); background-color:var(--bg-color); border-bottom-left-radius:16px; border-bottom-right-radius:16px; }
        .button-secondary { background-color:#e9ecef; color:var(--text-primary); border:1px solid #dee2e6; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background-color 0.2s; }
        .button-secondary:hover { background-color:#ced4da; }
        .button-primary { background-color:var(--button-bg); color:var(--button-text); border:none; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background-color 0.2s; }
        .button-primary:hover { background-color:#495057; }
    </style>
</head>
<body>
    @include('partials._sidebar-admin')

    <main class="main-content">
        <main class="container">
            <header>
                <h1>Manajemen Akademik</h1>
                <p class="subtitle">Kelola kelas dan mata pelajaran di sistem LMS</p>
            </header>

            <div class="tabs">
                <button id="kelasTab" class="tab-link active">Kelas</button>
                <button id="mapelTab" class="tab-link">Mata Pelajaran</button>
            </div>

            <div class="card">
                <div id="kelasContent">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Daftar Kelas</h2>
                            <p>Kelola semua kelas yang ada di sekolah</p>
                        </div>
                        <button class="add-button" id="openKelasModalBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Tambah Kelas</span>
                        </button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classes as $class)
                                <tr>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->students_count ?? 0 }} siswa</td>
                                    <td>{{ $class->academic_year }}</td>
                                    <td>
                                        @if($class->is_active)
                                            <span class="status-badge active">Aktif</span>
                                        @else
                                            <span class="status-badge">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('admin.academic.classes.show', $class) }}" class="icon-button" aria-label="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            <span>Detail</span>
                                        </a>
                                        <button type="button" class="icon-button edit-class-btn" aria-label="Edit"
                                            data-id="{{ $class->id }}"
                                            data-name="{{ $class->name }}"
                                            data-academic_year="{{ $class->academic_year }}"
                                            data-level="{{ $class->level }}"
                                            data-major="{{ $class->major }}"
                                            data-is_active="{{ $class->is_active ? 1 : 0 }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" onsubmit="return confirm('Hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-button delete" aria-label="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Belum ada data kelas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="mapelContent" class="hidden">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Daftar Mata Pelajaran</h2>
                            <p>Kelola semua mata pelajaran yang tersedia</p>
                        </div>
                        <button class="add-button" id="openMapelModalBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Tambah Mata Pelajaran</span>
                        </button>
                    </div>
                    <div class="subject-list">
                        @forelse($courses as $course)
                        <div class="subject-card">
                            <div class="subject-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                            <div class="subject-info">
                                <h3>{{ $course->name }}</h3>
                                <p>{{ $course->description }}</p>
                                <span>{{ $course->teachings->count() }} pengajaran</span>
                            </div>
                            <div class="actions">
                                <button type="button" class="icon-button" aria-label="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <span>Detail</span>
                                </button>
                                <button type="button" class="icon-button edit-course-btn" aria-label="Edit"
                                        data-id="{{ $course->id }}"
                                        data-name="{{ $course->name }}"
                                        data-description="{{ $course->description }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-button delete" aria-label="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="subject-card">
                            <div class="subject-info">
                                <p>Belum ada data mata pelajaran.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </main>
    <!-- Overlay dan Modal -->
    <div id="modalOverlay" class="modal-overlay hidden"></div>

    <div id="modalTambahKelas" class="modal hidden">
        <div class="modal-header">
            <div>
                <h2 id="kelasModalTitle">Tambah Kelas</h2>
                <p>Kelola data kelas</p>
            </div>
            <button class="close-button" id="closeKelasModalBtn">&times;</button>
        </div>
        <form id="kelasForm" method="POST" action="{{ route('admin.classes.store') }}">
            @csrf
            <input type="hidden" name="_method" id="kelasFormMethod" value="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="namaKelas">Nama Kelas</label>
                    <input type="text" id="namaKelas" name="name" placeholder="Contoh: X RPL 1">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tingkatan">Tingkatan</label>
                        <select id="tingkatan" name="level">
                            <option selected disabled>Pilih Tingkatan</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <select id="jurusan" name="major">
                            <option selected disabled>Pilih Jurusan</option>
                            <option value="RPL">RPL</option>
                            <option value="TKJ">TKJ</option>
                            <option value="Multimedia">Multimedia</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tahunAjaran">Tahun Ajaran</label>
                    <input type="text" id="tahunAjaran" name="academic_year" placeholder="Contoh: 2025/2026">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-secondary" id="batalKelasBtn">Batal</button>
                <button type="submit" class="button-primary" id="submitKelasBtn">Simpan</button>
            </div>
        </form>
    </div>

    <div id="modalTambahMapel" class="modal hidden">
        <div class="modal-header">
            <div>
                <h2 id="mapelModalTitle">Tambah Mata Pelajaran</h2>
                <p>Kelola data mata pelajaran</p>
            </div>
            <button class="close-button" id="closeMapelModalBtn">&times;</button>
        </div>
        <form id="mapelForm" method="POST" action="{{ route('admin.courses.store') }}">
            @csrf
            <input type="hidden" name="_method" id="mapelFormMethod" value="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="namaMapel">Nama Mata Pelajaran</label>
                    <input type="text" id="namaMapel" name="name" placeholder="Contoh: Pemrograman Web">
                </div>
                <div class="form-group">
                    <label for="deskripsiMapel">Deskripsi</label>
                    <textarea id="deskripsiMapel" name="description" placeholder="Deskripsi singkat tentang mata pelajaran"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-secondary" id="batalMapelBtn">Batal</button>
                <button type="submit" class="button-primary" id="submitMapelBtn">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        // --- LOGIKA TAB ---
        const kelasTab = document.getElementById('kelasTab');
        const mapelTab = document.getElementById('mapelTab');
        const kelasContent = document.getElementById('kelasContent');
        const mapelContent = document.getElementById('mapelContent');

        if (kelasTab && mapelTab && kelasContent && mapelContent) {
            kelasTab.addEventListener('click', () => {
                mapelTab.classList.remove('active');
                kelasTab.classList.add('active');
                mapelContent.classList.add('hidden');
                kelasContent.classList.remove('hidden');
            });

            mapelTab.addEventListener('click', () => {
                kelasTab.classList.remove('active');
                mapelTab.classList.add('active');
                kelasContent.classList.add('hidden');
                mapelContent.classList.remove('hidden');
            });
        }

        // --- LOGIKA MODAL (POPUP) ---
        const modalOverlay = document.getElementById('modalOverlay');

        // Modal Kelas
        const modalTambahKelas = document.getElementById('modalTambahKelas');
        const openKelasModalBtn = document.getElementById('openKelasModalBtn');
        const closeKelasModalBtn = document.getElementById('closeKelasModalBtn');
        const batalKelasBtn = document.getElementById('batalKelasBtn');
        const kelasForm = document.getElementById('kelasForm');
        const kelasFormMethod = document.getElementById('kelasFormMethod');
        const kelasModalTitle = document.getElementById('kelasModalTitle');
        const editClassButtons = document.querySelectorAll('.edit-class-btn');

        // Modal Mata Pelajaran
        const modalTambahMapel = document.getElementById('modalTambahMapel');
        const openMapelModalBtn = document.getElementById('openMapelModalBtn');
        const closeMapelModalBtn = document.getElementById('closeMapelModalBtn');
        const batalMapelBtn = document.getElementById('batalMapelBtn');
        const mapelForm = document.getElementById('mapelForm');
        const mapelFormMethod = document.getElementById('mapelFormMethod');
        const mapelModalTitle = document.getElementById('mapelModalTitle');
        const editCourseButtons = document.querySelectorAll('.edit-course-btn');

        // URL template untuk update
        const classUpdateUrlTemplate = "{{ route('admin.classes.update', ['class' => 'CLASS_ID']) }}";
        const courseUpdateUrlTemplate = "{{ route('admin.courses.update', ['course' => 'COURSE_ID']) }}";

        const openModal = (modal) => {
            if (!modal || !modalOverlay) return;
            modal.classList.remove('hidden');
            modalOverlay.classList.remove('hidden');
        };

        const closeModal = (modal) => {
            if (!modal || !modalOverlay) return;
            modal.classList.add('hidden');
            modalOverlay.classList.add('hidden');
        };

        if (openKelasModalBtn && closeKelasModalBtn && batalKelasBtn && modalTambahKelas) {
            openKelasModalBtn.addEventListener('click', () => {
                // mode tambah
                kelasModalTitle.textContent = 'Tambah Kelas';
                kelasForm.action = "{{ route('admin.classes.store') }}";
                kelasFormMethod.value = 'POST';
                document.getElementById('namaKelas').value = '';
                document.getElementById('tingkatan').value = '';
                document.getElementById('jurusan').value = '';
                document.getElementById('tahunAjaran').value = '';
                openModal(modalTambahKelas);
            });
            closeKelasModalBtn.addEventListener('click', () => closeModal(modalTambahKelas));
            batalKelasBtn.addEventListener('click', () => closeModal(modalTambahKelas));
        }

        // Edit kelas
        editClassButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const academic_year = btn.getAttribute('data-academic_year');
                const level = btn.getAttribute('data-level');
                const major = btn.getAttribute('data-major');

                kelasModalTitle.textContent = 'Edit Kelas';
                kelasForm.action = classUpdateUrlTemplate.replace('CLASS_ID', id);
                kelasFormMethod.value = 'PUT';
                document.getElementById('namaKelas').value = name || '';
                document.getElementById('tingkatan').value = level || '';
                document.getElementById('jurusan').value = major || '';
                document.getElementById('tahunAjaran').value = academic_year || '';
                openModal(modalTambahKelas);
            });
        });

        if (openMapelModalBtn && closeMapelModalBtn && batalMapelBtn && modalTambahMapel) {
            openMapelModalBtn.addEventListener('click', () => {
                // mode tambah
                mapelModalTitle.textContent = 'Tambah Mata Pelajaran';
                mapelForm.action = "{{ route('admin.courses.store') }}";
                mapelFormMethod.value = 'POST';
                document.getElementById('namaMapel').value = '';
                document.getElementById('deskripsiMapel').value = '';
                openModal(modalTambahMapel);
            });
            closeMapelModalBtn.addEventListener('click', () => closeModal(modalTambahMapel));
            batalMapelBtn.addEventListener('click', () => closeModal(modalTambahMapel));
        }

        // Edit mata pelajaran
        editCourseButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const description = btn.getAttribute('data-description');

                mapelModalTitle.textContent = 'Edit Mata Pelajaran';
                mapelForm.action = courseUpdateUrlTemplate.replace('COURSE_ID', id);
                mapelFormMethod.value = 'PUT';
                document.getElementById('namaMapel').value = name || '';
                document.getElementById('deskripsiMapel').value = description || '';
                openModal(modalTambahMapel);
            });
        });

        if (modalOverlay) {
            modalOverlay.addEventListener('click', () => {
                if (modalTambahKelas) closeModal(modalTambahKelas);
                if (modalTambahMapel) closeModal(modalTambahMapel);
            });
        }
    </script>

</body>
</html>
