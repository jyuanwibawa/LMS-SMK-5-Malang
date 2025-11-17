@include('partials._sidebar-siswa')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Kuis</title>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:40px; box-sizing:border-box; }

    .wrapper { max-width:820px; margin:0 auto; }
    .result-card { background:#fff; border:1px solid #E5E7EB; border-radius:24px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.03); text-align:center; }
    .icon { width:72px; height:72px; border-radius:9999px; display:inline-flex; align-items:center; justify-content:center; margin:0 auto 16px; background:#ECFDF5; color:#059669; }
    .icon svg { width:36px; height:36px; }
    .heading { font-size:22px; font-weight:800; color:#111827; margin:0 0 6px; }
    .sub { color:#6b7280; margin:0 0 20px; font-weight:500; }
    .score-box { background:#F3F4F6; border-radius:16px; padding:18px; width:240px; margin:0 auto 18px; }
    .score-title { color:#6b7280; font-weight:700; margin-bottom:6px; }
    .score-value { font-size:48px; font-weight:900; color:#111827; line-height:1; }
    .score-desc { color:#6b7280; margin-top:6px; }

    .label { text-align:left; font-weight:700; color:#111827; margin-top:18px; }
    .bar { height:12px; background:#111827; border-radius:9999px; }
    .bar-wrap { background:#E5E7EB; height:12px; border-radius:9999px; overflow:hidden; }

    .btn { margin-top:18px; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 18px; border-radius:12px; border:1px solid #111827; background:#111827; color:#fff; font-weight:800; text-decoration:none; width:100%; box-sizing:border-box; }
  </style>
</head>
<body>
  <main class="main">
    <div class="wrapper">
      @php($total = $assignment->questions->count())
      @php($answered = $submission?->answers?->count() ?? 0)
      @php($correct = 0)
      @foreach($assignment->questions as $q)
        @php($sel = optional($submission?->answers?->firstWhere('question_id', $q->id))->choice_id)
        @php($isRight = optional($q->choices->firstWhere('id', $sel))->is_correct ?? false)
        @php($correct += $isRight ? 1 : 0)
      @endforeach

      <div class="result-card">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="heading">Kuis Berhasil Dikumpulkan!</div>
        <div class="sub">Terima kasih telah mengerjakan kuis. Berikut hasil Anda:</div>

        <div class="score-box">
          <div class="score-title">Nilai Anda</div>
          <div class="score-value">{{ (int)($submission->grade ?? 0) }}</div>
          <div class="score-desc">{{ $correct }} dari {{ $total }} soal dijawab benar</div>
        </div>

        <div class="label">Soal dijawab <span style="float:right; font-weight:700;">{{ $answered }} / {{ $total }}</span></div>
        <div class="bar-wrap"><div class="bar" style="width: {{ $total>0 ? min(100, round(($answered/$total)*100)) : 0 }}%;"></div></div>

        <a class="btn" href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'tugas']) }}">Kembali ke Kelas</a>
      </div>
    </div>
  </main>
</body>
</html>