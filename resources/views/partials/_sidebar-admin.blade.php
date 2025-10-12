<aside class="sidebar">
    <div>
        {{-- Menampilkan data user yang sedang login --}}
        @auth
        <div class="admin-profile">
            <div class="avatar">
                {{-- Mengambil 2 huruf pertama dari nama user --}}
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <h4>{{ Auth::user()->name }}</h4>
            <p>{{ Auth::user()->email }}</p>
            <span class="tag"><i class='bx bxs-crown'></i> {{ ucfirst(Auth::user()->role->name) }}</span>
        </div>
        @endauth

        <nav class="navigation">
            <ul>
                {{-- Link Dashboard --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                        <i class='bx bxs-dashboard'></i> Dashboard
                    </a>
                </li>

                {{-- Link Manajemen Pengguna --}}
                <li>
                    <a href="#" class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                        <i class='bx bx-user'></i> Manajemen Pengguna
                    </a>
                </li>

                {{-- Link Manajemen Akademik --}}
                <li>
                    <a href="#" class="{{ Request::is('admin/academic*') ? 'active' : '' }}">
                        <i class='bx bx-book-alt'></i> Manajemen Akademik
                    </a>
                </li>

                {{-- Link Log Aktivitas --}}
                <li>
                    <a href="#" class="{{ Request::is('admin/logs*') ? 'active' : '' }}">
                        <i class='bx bx-line-chart'></i> Log Aktivitas
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="logout-section">
        {{-- Tombol Logout yang aman menggunakan form POST --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class='bx bx-log-out'></i> Keluar
            </a>
        </form>
    </div>
</aside>