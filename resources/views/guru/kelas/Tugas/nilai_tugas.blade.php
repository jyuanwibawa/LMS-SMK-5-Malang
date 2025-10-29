@include('partials._sidebar-guru')

<main class="main-content" style="margin-left:280px;padding:40px;box-sizing:border-box;">
  <div class="container" style="max-width:1200px;margin:0 auto;">

    <header class="page-header" style="margin-bottom:24px;">
      <a href="{{ route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="back-link" style="text-decoration:none;color:#4B5563;font-weight:500;display:inline-block;margin-bottom:12px;">← Kembali</a>
      <h1 style="margin:0 0 6px 0;">Penilaian Tugas: {{ $assignment->title }}</h1>
      <p class="subtitle" style="color:#6B7280;margin:0;">Nilai maksimal: 100</p>
    </header>

    <style>
      body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; color: #111827; }
      * { box-sizing: border-box; }
      .assessment-layout { display:flex; align-items:flex-start; gap:24px; }
      .card { background-color:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.06); }
      .card h2 { font-size:18px; font-weight:600; margin:0 0 16px 0; }
      .student-list-column { flex:1; min-width:300px; max-width:350px; }
      .grading-column { flex:2; }
      .student-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px; }
      .student-item { padding:16px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; transition:background-color .2s ease; display:grid; grid-template-columns:1fr auto; align-items:center; gap:12px; }
      .student-item:hover { background-color:#f9fafb; }
      .student-item.active { background-color:#f3f4f6; border-color:#d1d5db; }
      .student-info strong { display:block; font-size:15px; font-weight:600; color:#111827; }
      .student-info .nisn, .student-info .submission-time { font-size:12px; color:#6b7280; margin-top:4px; display:block; }
      .badge { font-size:12px; font-weight:500; padding:4px 10px; border-radius:999px; white-space:nowrap; }
      .badge.status-unscored { background-color:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
      .badge.score { background-color:#dcfce7; color:#166534; font-weight:600; }
      .badge.status-scored { background-color:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
      .grading-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; }
      .grading-header h3 { font-size:20px; font-weight:600; margin:0; }
      .grading-header .nisn { font-size:14px; color:#6b7280; margin-top:4px; }
      .file-submission-box { background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:20px; display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
      .file-info .file-name { font-size:14px; font-weight:500; color:#374151; margin:0 0 4px 0; }
      .file-info .submission-time { font-size:12px; color:#6b7280; margin:0; }
      .file-actions { display:flex; gap:12px; }
      .btn-icon { background-color:#fff; border:1px solid #d1d5db; color:#374151; padding:8px 14px; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background-color .2s ease; text-decoration:none; }
      .btn-icon:hover { background-color:#f9fafb; }
      .btn-icon .icon { font-size:16px; line-height:1; }
      .grading-form .form-group { margin-bottom:20px; }
      .grading-form label { display:block; font-size:14px; font-weight:500; margin-bottom:8px; color:#374151; }
      .grading-form input[type="number"], .grading-form textarea { width:100%; padding:12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; background-color:#fdfdfd; }
      .grading-form input[type="number"]:focus, .grading-form textarea:focus { outline:2px solid #3b82f6; border-color:#3b82f6; }
      .grading-form textarea { resize:vertical; min-height:80px; }
      .btn-primary { width:100%; background-color:#111827; color:#fff; font-size:15px; font-weight:500; padding:14px 20px; border:none; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:8px; transition:background-color .2s ease; }
      .btn-primary:hover { background-color:#374151; }
      @media (max-width: 900px) { .assessment-layout { flex-direction:column; } .student-list-column { width:100%; max-width:none; } }
    </style>

    <div class="assessment-layout">
      <aside class="student-list-column card">
        <h2>Daftar Pengumpulan</h2>
        <ul class="student-list">
          @forelse($assignment->submissions as $s)
            @php(
              $isActive = optional($currentSubmission)->id === $s->id
            )
            <li class="student-item {{ $isActive ? 'active' : '' }}" onclick="window.location='{{ route('guru.kelas.tugas.nilai', ['teaching'=>$teaching, 'assignment'=>$assignment, 'submission'=>$s->id]) }}'">
              <div class="student-info">
                <strong>{{ optional($s->user)->name ?? 'Tanpa Nama' }}</strong>
                <span class="nisn">{{ optional($s->user)->identity_number }}</span>
                <span class="submission-time">{{ optional($s->submitted_at)->format('d/m/Y, H.i.s') ?: '-' }}</span>
              </div>
              @if(is_null($s->grade))
                <span class="badge status-unscored">Belum Dinilai</span>
              @else
                <span class="badge score">{{ rtrim(rtrim(number_format($s->grade, 2, '.', ''), '0'), '.') }}</span>
              @endif
            </li>
          @empty
            <li class="student-item">
              <div class="student-info">
                <strong>Belum ada pengumpulan</strong>
                <span class="submission-time">-</span>
              </div>
            </li>
          @endforelse
        </ul>
      </aside>

      <section class="grading-column card">
        @if($currentSubmission)
          <div class="grading-header">
            <div>
              <h3>{{ optional($currentSubmission->user)->name ?? 'Tanpa Nama' }}</h3>
              <span class="nisn">NISN: {{ optional($currentSubmission->user)->identity_number ?? '-' }}</span>
            </div>
            @if(is_null($currentSubmission->grade))
              <span class="badge status-unscored">Belum Dinilai</span>
            @else
              <span class="badge status-scored">Sudah Dinilai</span>
            @endif
          </div>

          <div class="file-submission-box">
            <div class="file-info">
              <p class="file-name">{{ basename($currentSubmission->file_path ?? '') ?: 'Tidak ada berkas' }}</p>
              <span class="submission-time">Dikumpulkan: {{ optional($currentSubmission->submitted_at)->format('d/m/Y, H.i.s') ?: '-' }}</span>
            </div>
            <div class="file-actions">
              @if(!empty($currentSubmission->file_path))
                <a href="{{ route('guru.kelas.tugas.submissions.download', [$teaching, $assignment, $currentSubmission]) }}" class="btn-icon">
                  <span class="icon">&#8681;</span> Unduh
                </a>
              @endif
            </div>
          </div>

          <form class="grading-form" method="POST" action="{{ route('guru.kelas.tugas.submissions.update_grade', [$teaching, $assignment, $currentSubmission]) }}">
            @csrf
            <div class="form-group">
              <label for="nilai">Nilai</label>
              <input type="number" id="nilai" name="nilai" step="0.01" min="0" max="100" value="{{ old('nilai', $currentSubmission->grade) }}">
            </div>
            <div class="form-group">
              <label for="feedback">Feedback</label>
              <textarea id="feedback" name="feedback" rows="4">{{ old('feedback', $currentSubmission->feedback) }}</textarea>
            </div>
            <button type="submit" class="btn-primary"><span class="icon">&#128427;</span> Simpan Nilai</button>
          </form>
        @else
          <p style="color:#6b7280;">Belum ada pengumpulan untuk tugas ini.</p>
        @endif
      </section>
    </div>

  </div>
</main>
