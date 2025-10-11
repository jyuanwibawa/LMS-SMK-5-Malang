<aside class="sidebar">
    <div>
        <h1 class="logo">LMS Portal</h1>

        {{-- Menampilkan data user yang sedang login --}}
        @auth
        <div class="user-profile">
            <div class="avatar">
                {{-- Mengambil 2 huruf pertama dari nama --}}
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <h4>{{ Auth::user()->name }}</h4>
                <p>{{ Auth::user()->identity_number }} | {{ ucfirst(Auth::user()->role->name) }}</p>
            </div>
        </div>
        @endauth

        <nav class="navigation">
            <ul>
                {{-- ================== MULAI PERUBAHAN ================== --}}

                {{-- Kelas 'active' akan ditambahkan jika URL saat ini adalah 'siswa/dashboard' --}}
                <li>
                    <a href="{{-- route('siswa.dashboard') --}}"
                        class="{{ Request::is('siswa/dashboard*') ? 'active' : '' }}">
                        <i class='bx bxs-dashboard'></i> Dashboard
                    </a>
                </li>

                {{-- Kelas 'active' akan ditambahkan jika URL saat ini mengandung 'siswa/kelas' --}}
                <li>
                    <a href="{{-- route('siswa.kelas') --}}" class="{{ Request::is('siswa/kelas*') ? 'active' : '' }}">
                        <i class='bx bx-book-alt'></i> Kelas Saya
                    </a>
                </li>

                {{-- Kelas 'active' akan ditambahkan jika URL saat ini mengandung 'siswa/profil' --}}
                <li>
                    <a href="{{-- route('siswa.profil') --}}"
                        class="{{ Request::is('siswa/profil*') ? 'active' : '' }}">
                        <i class='bx bx-user'></i> Profil
                    </a>
                </li>

                {{-- ================== AKHIR PERUBAHAN ================== --}}
            </ul>
        </nav>
    </div>

    <div class="logout-section">
        {{-- Form untuk logout yang aman --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class='bx bx-log-out'></i> Keluar
            </a>
        </form>
    </div>
</aside>

{{-- Pastikan Anda memasukkan file CSS di layout utama Anda, bukan di dalam partial ini --}}