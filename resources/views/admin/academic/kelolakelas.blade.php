
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Kelas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root { --bg-color:#f8f9fa; --card-bg:#ffffff; --text-primary:#212529; --text-secondary:#6c757d; --border-color:#dee2e6; --accent-color-light:#e7f5ee; --accent-color-dark:#28a745; --button-bg:#212529; --button-text:#ffffff; --danger-color:#dc3545; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter', sans-serif; background-color:var(--bg-color); color:var(--text-primary); line-height:1.6; }
        .hidden { display:none; }

        /* Layout dengan sidebar */
        .main-content { margin-left:300px; padding:2rem; }
        .container { max-width:1200px; margin:0 auto; padding:2rem; background:transparent; }

        /* Sidebar styles (match admin sidebar) */
        .sidebar { width:300px; height:100vh; background-color:#FFFFFF; border-right:1px solid #E2E8F0; padding:24px; display:flex; flex-direction:column; justify-content:space-between; position:fixed; left:0; top:0; }
        .admin-profile { text-align:center; margin-bottom:20px; }
        .admin-profile .avatar { width:64px; height:64px; border-radius:50%; background-color:#121212; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:600; font-size:24px; margin-bottom:12px; }
        .admin-profile h4 { font-size:20px; font-weight:600; }
        .admin-profile p { font-size:14px; color:#6c757d; margin-bottom:16px; }
        .admin-profile .tag { background-color:#FEF3C7; color:#D97706; font-size:12px; font-weight:600; padding:6px 12px; border-radius:8px; display:inline-flex; align-items:center; gap:6px; }
        .navigation { border-top:1px solid #E2E8F0; padding-top:20px; }
        .navigation ul { list-style:none; }
        .navigation ul li a { display:flex; align-items:center; padding:14px 20px; margin-bottom:10px; border-radius:12px; text-decoration:none; color:#6c757d; font-weight:600; font-size:16px; transition:all .2s; }
        .navigation ul li a i { font-size:24px; margin-right:16px; }
        .navigation ul li a:hover { background-color:#F1F5F9; color:#1A202C; }
        .navigation ul li a.active { background-color:#121212; color:#fff; }
        .logout-section { border-top:1px solid #E2E8F0; padding-top:12px; }
        .logout-section a { display:flex; align-items:center; padding:14px 20px; text-decoration:none; color:#EF4444; font-weight:600; font-size:16px; border-radius:12px; }
        .logout-section a:hover { background-color:#FEF2F2; }
        .logout-section a i { font-size:24px; margin-right:16px; transform:scaleX(-1); }

        /* Header detail */
        header.detail-header { margin-bottom:1.5rem; }
        .back-button { display:inline-flex; align-items:center; gap:0.5rem; font-size:1rem; font-weight:600; color:var(--text-primary); background:none; border:none; cursor:pointer; margin-bottom:1rem; padding:0; }
        .back-button:hover { color:var(--text-secondary); }
        header h1 { font-size:1.8rem; font-weight:700; margin-bottom:0.25rem; }
        .subtitle { color:var(--text-secondary); font-size:1rem; }

        /* Tabs */
        .tabs { margin:2rem 0 1.5rem 0; background-color:#e9ecef; border-radius:12px; padding:5px; display:inline-block; }
        .tab-link { border:none; padding:0.75rem 1.5rem; border-radius:9px; cursor:pointer; font-size:0.9rem; font-weight:600; transition:all 0.2s; }
        .tab-link.active { background-color:var(--card-bg); color:var(--text-primary); box-shadow:0 2px 4px rgba(0,0,0,0.05); }
        .tab-link:not(.active) { background-color:transparent; color:var(--text-secondary); }

        /* Card */
        .card { background-color:var(--card-bg); border:1px solid var(--border-color); border-radius:16px; padding:1.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .card-title h2 { font-size:1.25rem; font-weight:600; margin-bottom:0.2rem; }
        .card-title p { color:var(--text-secondary); font-size:0.9rem; }

        /* Tombol */
        .add-button { background-color:var(--button-bg); color:var(--button-text); border:none; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:0.5rem; transition:background-color 0.2s; }
        .add-button:hover { background-color:#343a40; }
        .icon-button { background:none; border:none; cursor:pointer; padding:0.25rem; color:var(--text-secondary); transition:color 0.2s; display:flex; align-items:center; }
        .icon-button svg { width:20px; height:20px; flex-shrink:0; }
        .icon-button:hover { color:var(--text-primary); }
        .icon-button span { margin-left:0.4rem; font-weight:500; font-size:0.9rem; color:var(--danger-color); transition:color 0.2s; }
        .icon-button:hover span { color:var(--danger-color); }
        .icon-button.delete:hover, .icon-button.delete:hover span { color:var(--danger-color); }

        /* Tabel */
        .table-container { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:1rem; text-align:left; vertical-align:middle; font-size:0.9rem; }
        thead th { font-weight:600; color:var(--text-secondary); border-bottom:1px solid var(--border-color); }
        tbody tr { border-bottom:1px solid var(--border-color); }
        tbody tr:last-child { border-bottom:none; }
        tbody td { color:var(--text-primary); font-weight:500; }
        tbody td.actions { width:1%; white-space:nowrap; }
        .status-badge { padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.8rem; font-weight:600; }
        .status-badge.active { background-color:var(--accent-color-light); color:var(--accent-color-dark); }
        .actions { display:flex; gap:0.75rem; align-items:center; }

        /* Modal */
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
        .form-group input { width:100%; padding:0.75rem; border:1px solid var(--border-color); border-radius:8px; font-size:0.9rem; font-family:'Inter', sans-serif; transition:border-color 0.2s, box-shadow 0.2s; }
        .form-group input:focus { border-color:#80bdff; outline:0; box-shadow:0 0 0 0.2rem rgba(0,123,255,.25); }
        .form-row { display:flex; gap:1rem; }
        .form-row .form-group { flex:1; }
        .modal-footer { display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.5rem; border-top:1px solid var(--border-color); background-color:var(--bg-color); border-bottom-left-radius:16px; border-bottom-right-radius:16px; }
        .button-secondary { background-color:#e9ecef; color:var(--text-primary); border:1px solid #dee2e6; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background-color 0.2s; }
        .button-secondary:hover { background-color:#ced4da; }
        .button-primary { background-color:var(--button-bg); color:var(--button-text); border:none; padding:0.7rem 1.2rem; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background-color 0.2s; }
        .button-primary:hover { background-color:#495057; }

        /* Better dropdown design */
        .select-wrapper { position: relative; }
        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.65rem 2.25rem 0.65rem 0.75rem;
            font-size: 0.95rem;
            color: var(--text-primary);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .select-wrapper select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
        }
        .select-wrapper .select-chevron {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            pointer-events: none; color: var(--text-secondary);
        }
        /* When using size attribute (listbox), keep consistent style */
        #pilihSiswa[size] { height: auto; background: #fff; }
        #cariSiswa { border-radius: 10px; }
    </style>
</head>
<body>
    @include('partials._sidebar-admin')

    <main class="main-content">
        <main class="container">
            <header class="detail-header">
                <button id="tombolKembali" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali</span>
                </button>
                <h1>Pengelolaan Kelas {{ $class->name }}</h1>
                <p class="subtitle">Tahun Ajaran {{ $class->academic_year }} • {{ $studentsCount }} siswa • {{ $teachersCount }} guru</p>
            </header>

            <div class="tabs">
                <button id="pesertaDidikTab" class="tab-link active">Peserta Didik</button>
                <button id="guruMapelTab" class="tab-link">Guru & Mata Pelajaran</button>
            </div>

            <div class="card">
                <div id="pesertaDidikContent">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Daftar Siswa</h2>
                            <p>Kelola siswa yang terdaftar di kelas ini</p>
                        </div>
                        <button class="add-button" id="openSiswaModalBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg>
                            <span>Tambah Siswa</span>
                        </button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr><th>Nama</th><th>NISN</th><th>Email</th><th>Status</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                @forelse($class->enrollments as $enroll)
                                <tr>
                                    <td>{{ $enroll->user->name }}</td>
                                    <td>{{ $enroll->user->identity_number }}</td>
                                    <td>{{ $enroll->user->email }}</td>
                                    <td><span class="status-badge active">Aktif</span></td>
                                    <td class="actions">
                                        <form method="POST" action="{{ route('admin.classes.enrollments.destroy', [$class, $enroll]) }}" onsubmit="return confirm('Keluarkan siswa ini dari kelas?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-button delete" aria-label="Keluarkan">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                                <span>Keluarkan</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Belum ada siswa terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="guruMapelContent" class="hidden">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Guru & Mata Pelajaran</h2>
                            <p>Daftar guru dan mapel yang diajarkan di kelas ini</p>
                        </div>
                    </div>
                    @if($class->teachings->isEmpty())
                        <p>Belum ada data guru dan mata pelajaran.</p>
                    @else
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Guru</th>
                                        <th>Mata Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class->teachings as $teach)
                                    <tr>
                                        <td>{{ $teach->user->name }}</td>
                                        <td>{{ $teach->course->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </main>

    <div id="modalOverlay" class="modal-overlay hidden"></div>

    <div id="modalTambahSiswa" class="modal hidden">
        <div class="modal-header">
            <div>
                <h2>Tambah Siswa</h2>
                <p>Pilih siswa yang akan dimasukkan ke kelas ini</p>
            </div>
            <button class="close-button" id="closeSiswaModalBtn">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.classes.enrollments.store', $class) }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="pilihSiswa">Pilih Siswa</label>
                    <input type="text" id="cariSiswa" placeholder="Cari nama / NISN / email" style="width:100%; padding:0.65rem; border:1px solid var(--border-color); border-radius:8px; margin-bottom:0.75rem;">
                    <div class="select-wrapper">
                        <select id="pilihSiswa" name="user_id" style="width:100%;"
                                {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>
                            @if($availableStudents->isEmpty())
                                <option disabled>Tidak ada siswa tersedia</option>
                            @else
                                <option value="" disabled selected>— Pilih siswa —</option>
                                @foreach($availableStudents as $stu)
                                    <option value="{{ $stu->id }}">{{ $stu->name }} • {{ $stu->identity_number }} • {{ $stu->email }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="select-chevron">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </span>
                    </div>
                    @if(!$availableStudents->isEmpty())
                        <small id="jumlahSiswaTersedia" style="display:block; color:var(--text-secondary); margin-top:0.5rem;">{{ $availableStudents->count() }} siswa tersedia</small>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-secondary" id="batalSiswaBtn">Batal</button>
                <button type="submit" class="button-primary" id="submitTambahSiswaBtn" {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>Simpan</button>
            </div>
        </form>
    </div>

    <script>
        // --- Ambil Semua Elemen ---
        const tombolKembali = document.getElementById('tombolKembali');
        // Elemen Tab
        const pesertaDidikTab = document.getElementById('pesertaDidikTab');
        const guruMapelTab = document.getElementById('guruMapelTab');
        const pesertaDidikContent = document.getElementById('pesertaDidikContent');
        const guruMapelContent = document.getElementById('guruMapelContent');
        // Elemen Modal
        const modalOverlay = document.getElementById('modalOverlay');
        const modalTambahSiswa = document.getElementById('modalTambahSiswa');
        const openSiswaModalBtn = document.getElementById('openSiswaModalBtn');
        const closeSiswaModalBtn = document.getElementById('closeSiswaModalBtn');
        const batalSiswaBtn = document.getElementById('batalSiswaBtn');

        // Tombol Kembali
        tombolKembali.addEventListener('click', () => { history.back(); });

        // Tabs
        pesertaDidikTab.addEventListener('click', () => {
            guruMapelTab.classList.remove('active');
            pesertaDidikTab.classList.add('active');
            guruMapelContent.classList.add('hidden');
            pesertaDidikContent.classList.remove('hidden');
        });
        guruMapelTab.addEventListener('click', () => {
            pesertaDidikTab.classList.remove('active');
            guruMapelTab.classList.add('active');
            pesertaDidikContent.classList.add('hidden');
            guruMapelContent.classList.remove('hidden');
        });

        // Modal helpers
        const openModal = (modal) => { modal.classList.remove('hidden'); modalOverlay.classList.remove('hidden'); };
        const closeModal = (modal) => { modal.classList.add('hidden'); modalOverlay.classList.add('hidden'); };

        // Buka/Tutup Modal
        if (openSiswaModalBtn) openSiswaModalBtn.addEventListener('click', () => openModal(modalTambahSiswa));
        if (closeSiswaModalBtn) closeSiswaModalBtn.addEventListener('click', () => closeModal(modalTambahSiswa));
        if (batalSiswaBtn) batalSiswaBtn.addEventListener('click', () => closeModal(modalTambahSiswa));
        if (modalOverlay) modalOverlay.addEventListener('click', () => closeModal(modalTambahSiswa));

        // Pencarian dan validasi pemilihan siswa
        const cariSiswa = document.getElementById('cariSiswa');
        const pilihSiswa = document.getElementById('pilihSiswa');
        const submitTambahSiswaBtn = document.getElementById('submitTambahSiswaBtn');
        const jumlahSiswaTersedia = document.getElementById('jumlahSiswaTersedia');

        if (cariSiswa && pilihSiswa) {
            const filterOptions = () => {
                const term = cariSiswa.value.toLowerCase();
                let visibleCount = 0;
                Array.from(pilihSiswa.options).forEach((opt) => {
                    // skip placeholder disabled option
                    if (opt.disabled && !opt.value) return;
                    const match = opt.text.toLowerCase().includes(term);
                    opt.hidden = !match;
                    if (match) visibleCount++;
                });
                if (jumlahSiswaTersedia) jumlahSiswaTersedia.textContent = visibleCount + ' siswa cocok';
            };
            cariSiswa.addEventListener('input', filterOptions);
        }

        if (pilihSiswa && submitTambahSiswaBtn) {
            const updateSubmitState = () => {
                submitTambahSiswaBtn.disabled = !pilihSiswa.value;
            };
            pilihSiswa.addEventListener('change', updateSubmitState);
            // init
            updateSubmitState();
        }
    </script>

</body>
</html>

