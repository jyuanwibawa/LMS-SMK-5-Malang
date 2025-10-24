<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Sidebar */
        :root {
            --card-bg: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #718096;
            --border-color: #E2E8F0;
            --dark: #121212;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 300px;
            height: 100vh;
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
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
            background-color: var(--dark);
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
            background-color: var(--dark);
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

        /* CSS Konten Utama */
        .main-content {
            margin-left: 300px;
            /* Jarak untuk sidebar */
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        .page-header p {
            font-size: 1rem;
            color: #6b7280;
        }

        .add-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #111827;
            color: #ffffff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .user-card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            overflow: hidden;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-header p {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .user-table-wrapper {
            overflow-x: auto;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 1.5rem;
        }

        .user-table th,
        .user-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            white-space: nowrap;
        }

        .user-table th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
        }

        .user-table .user-name {
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-siswa {
            background-color: #e0f2fe;
            color: #075985;
        }

        .badge-guru {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-admin {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-aktif {
            background-color: #dcfce7;
            color: #166534;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-btn {
            background: none;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            color: #6b7280;
        }

        .action-btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>

<body>

    @include('partials._sidebar-admin')

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Manajemen Pengguna</h1>
                <p>Kelola data guru dan siswa di sistem LMS</p>
            </div>
            <button id="show-add-modal-btn" class="add-user-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pengguna
            </button>
        </header>

        <div class="user-card">
            <div class="card-header">
                <h2>Daftar Pengguna</h2>
                <p>Total {{ $users->count() }} pengguna ditemukan</p>
            </div>

            <div class="user-table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NISN/NUPTK</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="user-name">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                @if ($user->role->name == 'siswa')
                                <span class="badge badge-siswa">Siswa</span>
                                @elseif ($user->role->name == 'guru')
                                <span class="badge badge-guru">Guru</span>
                                @else
                                <span class="badge badge-admin">Admin</span>
                                @endif
                                @else
                                <span>-</span>
                                @endif
                            </td>
                            <td>{{ $user->identity_number ?? '-' }}</td>
                            <td><span class="badge badge-aktif">Aktif</span></td>
                            <td class="actions">
                                <button class="action-btn edit-btn" title="Edit"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg></button>
                                <button class="action-btn delete-btn" title="Delete"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Tidak ada data pengguna yang
                                ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Modal Anda dari kode asli tetap di sini --}}
    <div id="user-modal" class="modal-overlay">
        {{-- Konten Modal --}}
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen Modal
            const modal = document.getElementById('user-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalSubmitBtn = document.getElementById('modal-submit-btn').querySelector('span');
            const userForm = document.getElementById('user-form');

            // Tombol Pembuka Modal
            const openAddModalBtn = document.getElementById('show-add-modal-btn');
            const editBtns = document.querySelectorAll('.edit-btn');

            // Tombol Penutup Modal
            const closeBtns = document.querySelectorAll('.close-modal-btn');

            const openModal = () => modal.classList.add('active');
            const closeModal = () => modal.classList.remove('active');

            // Event listener untuk membuka modal "Tambah Pengguna"
            openAddModalBtn.addEventListener('click', () => {
                modalTitle.textContent = 'Tambah Pengguna Baru';
                modalSubmitBtn.textContent = 'Tambah Pengguna';
                userForm.reset();
                openModal();
            });

            // Event listener untuk setiap tombol "Edit"
            editBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    modalTitle.textContent = 'Edit Pengguna';
                    modalSubmitBtn.textContent = 'Simpan Perubahan';

                    const userRow = e.currentTarget.closest('tr');
                    const userData = userRow.dataset;

                    // Isi form dengan data yang ada
                    userForm.querySelector('[name="nama"]').value = userData.name;
                    userForm.querySelector('[name="email"]').value = userData.email;
                    userForm.querySelector('[name="role"]').value = userData.role;

                    // ==== JAVASCRIPT BARU: Mengisi data NISN & Jenis Kelamin ====
                    userForm.querySelector('[name="nisn_nuptk"]').value = userData.nisn;

                    // Pilih radio button yang sesuai
                    if (userData.gender) {
                        const genderRadio = userForm.querySelector(
                            `input[name="jenis_kelamin"][value="${userData.gender}"]`);
                        if (genderRadio) {
                            genderRadio.checked = true;
                        }
                    } else {
                        // Jika data gender tidak ada, kosongkan pilihan
                        userForm.querySelectorAll('input[name="jenis_kelamin"]').forEach(radio =>
                            radio.checked = false);
                    }

                    userForm.querySelector('[name="password"]').value = '';
                    userForm.querySelector('[name="konfirmasi_password"]').value = '';

                    openModal();
                });
            });

            // Event listener untuk semua tombol penutup modal
            closeBtns.forEach(btn => {
                btn.addEventListener('click', closeModal);
            });

            // Event listener untuk menutup modal saat klik di luar area konten
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>

</body>

</html>