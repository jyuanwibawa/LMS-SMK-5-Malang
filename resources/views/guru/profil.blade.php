<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Guru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; background:#F7F8FC; margin:0; }
        .main-content { margin-left:280px; padding:32px 40px; box-sizing:border-box; }
        .main-header h2 { font-size:26px; font-weight:800; margin:0 0 4px; color:#111827; }
        .main-header p { margin:0; color:#6B7280; }

        .grid {
            margin-top:24px;
            display:grid;
            grid-template-columns:0.9fr 2.1fr;
            gap:20px;
            align-items:flex-start;
        }

        .card {
            background:#fff;
            border-radius:18px;
            border:1px solid #E5E7EB;
            padding:20px 22px;
        }

        .avatar-wrapper {
            display:flex;
            flex-direction:column;
            align-items:center;
            text-align:center;
            gap:10px;
        }
        .avatar-circle {
            width:80px; height:80px;
            border-radius:999px;
            background:#111827;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            font-size:24px;
        }
        .role-badge {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:4px 12px;
            border-radius:999px;
            background:#111827;
            color:#fff;
            font-size:11px;
            font-weight:600;
        }
        .muted { font-size:13px; color:#6B7280; }

        .stats-list { margin-top:16px; display:flex; flex-direction:column; gap:6px; font-size:13px; }
        .stats-row { display:flex; justify-content:space-between; color:#111827; }
        .stats-label { color:#6B7280; }

        .section-title { font-size:16px; font-weight:700; margin:0 0 4px; }
        .section-sub { font-size:13px; color:#6B7280; margin:0 0 16px; }

        .top-row {
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:12px;
        }
        .btn-ghost {
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:999px;
            border:1px solid #E5E7EB;
            background:#fff;
            font-size:13px;
            font-weight:500;
            cursor:pointer;
        }

        .form-grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:14px 16px;
        }
        .form-group { display:flex; flex-direction:column; gap:4px; }
        .form-label { font-size:13px; font-weight:600; color:#4B5563; }
        .input-shell {
            display:flex;
            align-items:center;
            gap:8px;
            background:#F3F4F6;
            border-radius:999px;
            padding:6px 12px;
            font-size:13px;
            color:#111827;
        }
        .input-shell span.icon {
            display:inline-flex;
            width:20px; height:20px;
            align-items:center; justify-content:center;
            border-radius:999px;
            background:#E5E7EB;
            color:#4B5563;
            font-size:12px;
        }
        .input-field {
            border:none;
            background:transparent;
            width:100%;
            font-size:13px;
            outline:none;
        }
        .chip {
            width:100%;
            border-radius:999px;
            background:#F3F4F6;
            padding:8px 14px;
            font-size:13px;
            color:#111827;
        }

        .status-alert {
            margin-bottom:12px; padding:8px 12px; border-radius:10px;
            font-size:13px; background:#ECFDF5; color:#166534;
            border:1px solid #BBF7D0;
        }
        .error { font-size:12px; color:#DC2626; margin-top:2px; }

        .actions-row {
            margin-top:18px;
            display:flex;
            justify-content:flex-end;
        }
        .btn-primary {
            display:inline-flex; align-items:center; justify-content:center;
            padding:9px 18px; border-radius:999px; border:none;
            background:#111827; color:#fff; font-size:14px; font-weight:600;
            cursor:pointer;
        }
    </style>
</head>
<body>
    @include('partials._sidebar-guru')

    <main class="main-content">
        <header class="main-header">
            <h2>Profil Saya</h2>
            <p>Kelola informasi pribadi Anda</p>
        </header>

        <div class="grid">
            {{-- Kolom kiri: avatar & statistik --}}
            <section class="card">
                @php
                    $name = $user->name ?? '';
                    $initials = $name ? strtoupper(mb_substr($name, 0, 2)) : 'GR';
                @endphp

                <div class="avatar-wrapper">
                    <div class="avatar-circle">{{ $initials }}</div>
                    <div style="font-weight:600; color:#111827;">{{ $user->name }}</div>
                    <div class="muted">
                        NUPTK:<br>
                        {{ $user->identity_number ?? '-' }}
                    </div>
                    <span class="role-badge">Guru</span>
                </div>

                <div style="margin-top:24px; border-top:1px solid #E5E7EB; padding-top:14px;">
                    <div class="section-title" style="font-size:14px;">Statistik Mengajar</div>
                    <div class="stats-list">
                        <div class="stats-row">
                            <span class="stats-label">Total Kelas</span>
                            <span>{{ $totalClasses ?? 0 }}</span>
                        </div>
                        <div class="stats-row">
                            <span class="stats-label">Total Siswa</span>
                            <span>{{ $totalStudents ?? 0 }}</span>
                        </div>
                        <div class="stats-row">
                            <span class="stats-label">Materi</span>
                            <span>{{ $totalMaterials ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Kolom kanan: informasi pribadi & form --}}
            <section class="card">
                <div class="top-row">
                    <div>
                        <h3 class="section-title">Informasi Pribadi</h3>
                        <p class="section-sub">Data diri dan kontak Anda</p>
                    </div>
                    <button type="button" class="btn-ghost">
                        Edit
                    </button>
                </div>

                @if(session('status'))
                    <div class="status-alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('guru.profil.update') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Nama Lengkap</label>
                            <div class="input-shell">
                                <span class="icon">
                                    {{-- icon user --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input id="name" type="text" name="name"
                                       class="input-field"
                                       value="{{ old('name', $user->name) }}">
                            </div>
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">NUPTK</label>
                            <div class="input-shell">
                                <span class="icon">
                                    {{-- icon id card --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                        <line x1="8" y1="10" x2="16" y2="10"></line>
                                        <line x1="8" y1="14" x2="12" y2="14"></line>
                                    </svg>
                                </span>
                                <input type="text" class="input-field"
                                       value="{{ $user->identity_number ?? '-' }}" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-shell">
                                <span class="icon">
                                    {{-- icon mail --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <polyline points="3 7 12 13 21 7"></polyline>
                                    </svg>
                                </span>
                                <input id="email" type="email" name="email"
                                       class="input-field"
                                       value="{{ old('email', $user->email) }}">
                            </div>
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Form telepon dihilangkan --}}

                        <div class="form-group" style="grid-column:1 / -1;">
                            <label class="form-label">Mata Pelajaran yang Diajar</label>
                            <div class="chip">
                                @if(isset($subjects) && $subjects->isNotEmpty())
                                    {{ $subjects->join(', ') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="form-group" style="grid-column:1 / -1;">
                            <label class="form-label" for="password">Password Baru</label>
                            <div class="input-shell">
                                <span class="icon">
                                    {{-- icon lock --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="11" width="16" height="9" rx="2"></rect>
                                        <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                                    </svg>
                                </span>
                                <input id="password" type="password" name="password" class="input-field">
                            </div>
                            <div class="muted" style="font-size:12px;">
                                Kosongkan jika tidak ingin mengubah password.
                            </div>
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="actions-row">
                        <button type="submit" class="btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</body>
</html>