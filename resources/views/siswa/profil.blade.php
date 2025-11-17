@include('partials._sidebar-siswa')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Siswa</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:32px 40px; box-sizing:border-box; }
    .container { max-width:1100px; margin:0 auto; }
    .title { font-size:32px; font-weight:800; color:#111827; margin:0 0 4px; }
    .subtitle { color:#6b7280; margin:0 0 20px; }

    .grid { display:grid; grid-template-columns: 1fr 340px; gap:18px; align-items:start; }

    .card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:18px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
    .card-title { font-weight:800; color:#111827; margin:0 0 4px; }
    .card-sub { color:#6b7280; margin:0 0 12px; }

    .row { display:grid; grid-template-columns: 1fr 1fr; gap:14px; }
    .field { display:flex; flex-direction:column; gap:6px; }
    .label { font-weight:700; color:#111827; }
    .input { width:100%; padding:12px 14px; border:1px solid #E5E7EB; border-radius:12px; background:#F3F4F6; color:#111827; }
    .input[disabled] { opacity:1; cursor:not-allowed; }
    .error { color:#b91c1c; font-size:13px; }

    .toolbar { display:flex; justify-content:flex-end; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px solid #E5E7EB; background:#fff; color:#111827; font-weight:800; cursor:pointer; }
    .btn-primary { background:#111827; color:#fff; border-color:#111827; }

    .profile-avatar { width:120px; height:120px; border-radius:9999px; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; font-size:34px; font-weight:800; margin:0 auto 10px; }
    .center { text-align:center; }
    .chip { display:inline-block; background:#111827; color:#fff; padding:6px 10px; border-radius:9999px; font-weight:800; font-size:12px; }
    .meta { display:grid; grid-template-columns: 1fr 1fr; gap:8px 12px; color:#6b7280; margin-top:14px; }
    .meta div { display:flex; justify-content:space-between; gap:8px; }
    .meta strong { color:#111827; }

    /* Stats */
    .grid-2 { display:grid; grid-template-columns: 1fr 340px; gap:18px; align-items:start; }
    .stats { display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-top:8px; }
    .stat { background:#E5E7EB; border-radius:16px; padding:16px; }
    .stat .name { color:#6b7280; font-weight:700; margin-bottom:8px; }
    .stat .value { font-size:28px; font-weight:900; color:#111827; }
    .stat .value.accent { color:#16a34a; }
  </style>
</head>
<body>
  <main class="main">
    <div class="container">
      <h1 class="title">Profil Saya</h1>
      <div class="subtitle">Kelola informasi pribadi dan akademik Anda</div>

      @if (session('status'))
        <div class="card" style="border-color:#A7F3D0; background:#ECFDF5; color:#065F46;">{{ session('status') }}</div>
      @endif

      <div class="grid">
        <form id="profileForm" class="card" method="POST" action="{{ route('siswa.profil.update') }}">
          @csrf
          <div class="toolbar"><button id="editBtn" type="button" class="btn">✏️ Edit</button></div>
          <div class="card-title">Informasi Pribadi</div>
          <div class="card-sub">Data diri dan kontak Anda</div>

          <div class="row">
            <div class="field">
              <label class="label">Nama Lengkap</label>
              <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" disabled>
              @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label class="label">NISN</label>
              <input class="input" type="text" value="{{ $user->identity_number ?? '-' }}" disabled>
            </div>
          </div>

          <div class="row">
            <div class="field">
              <label class="label">Email</label>
              <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" disabled>
              @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label class="label">Jenis Kelamin</label>
              <input class="input" type="text" name="gender" value="{{ old('gender', $user->gender ?? '') }}" disabled>
            </div>
          </div>

          <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:16px;">
            <button id="cancelBtn" type="button" class="btn" style="display:none;">Batal</button>
            <button id="saveBtn" type="submit" class="btn btn-primary" style="display:none;">Simpan</button>
          </div>
        </form>

        <div class="card">
          <div class="profile-avatar">{{ strtoupper(substr($user->name,0,2)) }}</div>
          <div class="center" style="font-weight:800; color:#111827;">{{ $user->name }}</div>
          <div class="center" style="color:#6b7280;">NISN: {{ $user->identity_number ?? '-' }}</div>
          <div class="center" style="margin-top:6px;"><span class="chip">Siswa</span></div>
        </div>
      </div>
    </div>

    @php(
      $uid = auth()->id()
    )
    @php(
      $graded = \App\Models\Submission::where('user_id',$uid)->whereNotNull('grade')->get()
    )
    @php($avgGrade = $graded->isEmpty() ? '-' : number_format($graded->avg('grade'),0))
    @php($tasksDone = \App\Models\Submission::where('user_id',$uid)->count())
    @php($tasksTotal = \App\Models\Assignment::count())
    @php($classesTotal = \App\Models\Enrollment::where('user_id',$uid)->count())

    <div class="grid-2" style="margin-top:18px;">
      <div class="card">
        <div class="card-title">Kinerja Akademik</div>
        <div class="card-sub">Ringkasan performa belajar Anda</div>
        <div class="stats">
          <div class="stat">
            <div class="name">Rata-rata Nilai</div>
            <div class="value">{{ $avgGrade }}</div>
          </div>
          <div class="stat">
            <div class="name">Tugas Selesai</div>
            <div class="value">{{ $tasksDone }}/{{ max($tasksTotal, $tasksDone) }}</div>
          </div>
          <div class="stat">
            <div class="name">Total Kelas</div>
            <div class="value">{{ $classesTotal }}</div>
          </div>
        </div>
      </div>
      <div class="card" style="background:#F3F4F6;">
        <div class="card-title">Tips Profil</div>
        <ul style="margin:8px 0 0 18px; color:#111827;">
          <li>Pastikan informasi kontak selalu up-to-date</li>
          <li>Gunakan email aktif untuk notifikasi</li>
          <li>Hubungi admin jika ada data yang perlu diubah</li>
        </ul>
      </div>
    </div>
  </div>
</main>

<script>
  (function(){
    const form = document.getElementById('profileForm');
    const editBtn = document.getElementById('editBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const inputs = Array.from(form.querySelectorAll('.input'));
    const editable = inputs.filter(i=> i.name === 'name' || i.name === 'email' || i.name === 'gender');

    function setEditing(on){
      editable.forEach(i=> i.disabled = !on);
      saveBtn.style.display = on ? 'inline-flex' : 'none';
      cancelBtn.style.display = on ? 'inline-flex' : 'none';
      editBtn.style.display = on ? 'none' : 'inline-flex';
    }

    editBtn.addEventListener('click', ()=> setEditing(true));
    cancelBtn.addEventListener('click', ()=>{ setEditing(false); form.reset(); });
  })();
</script>
</body>
</html>