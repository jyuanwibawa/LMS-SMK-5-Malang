@include('partials._sidebar-siswa')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kelas</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:40px; box-sizing:border-box; }
    .back { color:#374151; text-decoration:none; display:inline-flex; gap:8px; align-items:center; font-weight:600; }
    .title { margin:12px 0 4px; font-size:28px; font-weight:800; color:#111827; }
    .subtitle { margin:0 0 10px; color:#6b7280; font-weight:600; }
    .desc { color:#4b5563; margin:0 0 18px; }

    .tabs { display:flex; gap:2px; background:#f3f4f6; padding:6px; border-radius:9999px; width:100%; max-width:760px; }
    .tabs a { flex:1; text-align:center; padding:10px 14px; text-decoration:none; border-radius:9999px; font-weight:700; color:#111827; }
    .tabs a.active { background:#fff; box-shadow:0 1px 2px rgba(0,0,0,.05); }

    .tab-panel { margin-top:16px; }
    .card { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:18px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
  </style>
</head>
<body>
  <main class="main">
    <a href="{{ route('siswa.kelas.index') }}" class="back">&larr; Kembali</a>

    <h1 class="title">{{ $teaching->course->name }} {{ $teaching->schoolClass->name }}</h1>
    <div class="subtitle">{{ $teaching->user->name }}</div>
    <p class="desc">Mata pelajaran {{ $teaching->course->name }} untuk kelas {{ $teaching->schoolClass->name }}.</p>

    <nav class="tabs">
      <a href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'materi']) }}" class="{{ $activeTab === 'materi' ? 'active' : '' }}">Materi</a>
      <a href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'tugas']) }}" class="{{ $activeTab === 'tugas' ? 'active' : '' }}">Tugas dan Kuis</a>
      <a href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'nilai']) }}" class="{{ $activeTab === 'nilai' ? 'active' : '' }}">Nilai</a>
      <a href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'forum']) }}" class="{{ $activeTab === 'forum' ? 'active' : '' }}">Forum</a>
    </nav>

    <section class="tab-panel" id="tab-materi" style="display: {{ $activeTab === 'materi' ? 'block' : 'none' }};">
      @include('siswa.kelas.Materi.materi')
    </section>
    <section class="tab-panel" id="tab-tugas" style="display: {{ $activeTab === 'tugas' ? 'block' : 'none' }};">
      @include('siswa.kelas.Tugas.tabs')
    </section>
    <section class="tab-panel" id="tab-nilai" style="display: {{ $activeTab === 'nilai' ? 'block' : 'none' }};">
      @include('siswa.kelas.Nilai.index')
    </section>
    <section class="tab-panel" id="tab-forum" style="display: {{ $activeTab === 'forum' ? 'block' : 'none' }};">
      <div class="card">Forum belum tersedia.</div>
    </section>
  </main>
</body>
</html>
