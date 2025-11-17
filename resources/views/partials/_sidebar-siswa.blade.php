<style>
  .sidebar { background:#fff; border-right:1px solid #dee2e6; display:flex; flex-direction:column; padding:1.75rem; padding-bottom:2.5rem; width:280px; height:100vh; position:fixed; left:0; top:0; overflow-y:auto; box-sizing:border-box; }
  .sidebar-header { display:flex; align-items:center; gap:.75rem; margin-bottom:2rem; }
  .sidebar-header svg { width:28px; height:28px; }
  .sidebar-header span { font-size:1.25rem; font-weight:700; }
  .user-profile { display:flex; align-items:center; gap:.75rem; margin-bottom:1rem; }
  .avatar { width:48px; height:48px; border-radius:50%; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:600; }
  .user-info h4 { margin:0; font-size:.95rem; font-weight:600; color:#111827; }
  .user-info p { margin:.15rem 0 0; font-size:.85rem; color:#6b7280; }
  .user-role-badge { background:#f8f9fa; border:1px solid #e5e7eb; color:#6b7280; font-weight:500; padding:.35rem .9rem; border-radius:9999px; font-size:.9rem; text-align:center; margin: .5rem 0 1.25rem; }
  .navigation ul { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.5rem; }
  .navigation a { display:flex; align-items:center; gap:.8rem; padding:.85rem 1rem; border-radius:12px; text-decoration:none; font-weight:600; font-size:1rem; color:#6b7280; transition:background-color .2s,color .2s; min-height:48px; line-height:1; box-sizing:border-box; white-space:nowrap; }
  .navigation a:hover { background:#f8f9fa; color:#111827; }
  .navigation a.active { background:#111827; color:#fff; padding:.85rem 1rem; min-height:48px; }
  .navigation a svg { width:22px; height:22px; flex-shrink:0; }
  .logout-section { margin-top:auto; border-top:1px solid #dee2e6; padding-top:1.25rem; }
  .logout-section a { display:flex; align-items:center; gap:.8rem; padding:.8rem 1rem; border-radius:10px; text-decoration:none; font-weight:600; font-size:1rem; color:#dc3545; }
  .logout-section a:hover { background:#fdf2f2; }
</style>
<aside class="sidebar">
  <div>
    <div class="sidebar-header">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 7L12 12 2 7l10-5 10 5z"></path><path d="M6 10v6"></path><path d="M18 10v6"></path><path d="M4 21h16"></path></svg>
      <span>LMS Portal</span>
    </div>

    {{-- Menampilkan data user yang sedang login --}}
    @auth
    <div class="user-profile">
      <div class="avatar">
        {{-- Mengambil 2 huruf pertama dari nama --}}
        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
      </div>
      <div class="user-info">
        <h4>{{ Auth::user()->name }}</h4>
        <p>{{ Auth::user()->identity_number }}</p>
      </div>
    </div>
    <div class="user-role-badge">{{ ucfirst(optional(Auth::user()->role)->name ?? 'Siswa') }}</div>
    @endauth

    <nav class="navigation">
      <ul>
        {{-- ================== MULAI PERUBAHAN ================== --}}
        <li>
          <a href="{{ route('siswa.dashboard') }}" class="{{ Request::is('siswa/dashboard*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('siswa.kelas.index') }}" class="{{ Request::is('siswa/kelas*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <span>Kelas Saya</span>
          </a>
        </li>
        <li>
          <a href="{{ route('siswa.profil.show') }}" class="{{ Request::is('siswa/profil*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Profil</span>
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
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        <span>Keluar</span>
      </a>
    </form>
  </div>
</aside>

{{-- Pastikan Anda memasukkan file CSS di layout utama Anda, bukan di dalam partial ini --}}