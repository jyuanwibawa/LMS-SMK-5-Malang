<style>
    /* Sidebar styles migrated from html/siderbarguru.html */
    .sidebar { background-color:#ffffff; border-right:1px solid #dee2e6; display:flex; flex-direction:column; padding:1.75rem; padding-bottom:2.5rem; width:280px; height:100vh; position:fixed; left:0; top:0; overflow-y:auto; box-sizing:border-box; }
    .sidebar-header { display:flex; align-items:center; gap:0.75rem; margin-bottom:2rem; }
    .sidebar-header svg { width:28px; height:28px; }
    .sidebar-header span { font-size:1.25rem; font-weight:700; }
    .sidebar-user { display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem; }
    .user-avatar { width:48px; height:48px; border-radius:50%; background-color:#212529; color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:600; flex-shrink:0; }
    .user-info h3 { font-size:0.95rem; font-weight:600; color:#212529; }
    .user-info p { font-size:0.85rem; color:#6c757d; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .user-role-badge { background-color:#f8f9fa; border:1px solid #dee2e6; color:#6c757d; font-weight:500; padding:0.25rem 0.75rem; border-radius:8px; font-size:0.9rem; text-align:center; margin-bottom:1.5rem; }
    .sidebar-nav { flex-grow:1; }
    .sidebar-nav ul { list-style:none; display:flex; flex-direction:column; gap:0.5rem; padding:0; margin:0; }
    .sidebar-nav a { display:flex; align-items:center; gap:0.8rem; padding:0.8rem 1rem; border-radius:10px; text-decoration:none; font-weight:600; font-size:1rem; color:#6c757d; transition:background-color 0.2s, color 0.2s; }
    .sidebar-nav a svg { width:22px; height:22px; }
    .sidebar-nav a:hover { background-color:#f8f9fa; color:#212529; }
    .sidebar-nav a.active { background-color:#212529; color:#ffffff; }
    .sidebar-nav a.active svg { stroke:#ffffff; }
    .sidebar-footer { border-top:1px solid #dee2e6; padding-top:1.5rem; }
    .sidebar-footer a { display:flex; align-items:center; gap:0.8rem; padding:0.8rem 1rem; border-radius:10px; text-decoration:none; font-weight:600; font-size:1rem; color:#dc3545; transition:background-color 0.2s; }
    .sidebar-footer a:hover { background-color:#fdf2f2; }
    .sidebar-footer a svg { width:22px; height:22px; }
</style>
<aside class="sidebar">
    <div class="sidebar-header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        <span>LMS Portal</span>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            @auth
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            @else
                GU
            @endauth
        </div>
        <div class="user-info">
            <h3>@auth {{ Auth::user()->name }} @else Guru @endauth</h3>
            <p>@auth {{ Auth::user()->email }} @else guru@example.com @endauth</p>
        </div>
    </div>
    <div class="user-role-badge">
        @auth {{ ucfirst(optional(Auth::user()->role)->name ?? 'Guru') }} @else Guru @endauth
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('guru.dashboard') }}" class="{{ Request::is('guru/dashboard*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.kelas.index') }}" class="{{ Request::is('guru/kelas*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>Kelas Saya</span>
                </a>
            </li>
            <li>
                <a href="#" class="{{ Request::is('guru/profile*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Profil</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span>Keluar</span>
            </a>
        </form>
    </div>
</aside>

