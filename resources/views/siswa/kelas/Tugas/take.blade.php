@include('partials._sidebar-siswa')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kerjakan Kuis</title>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:24px 40px; box-sizing:border-box; }

    .header { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:12px; }
    .back { display:inline-flex; align-items:center; gap:8px; font-weight:700; color:#111827; text-decoration:none; }
    .timer { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:10px 14px; display:flex; align-items:center; gap:8px; font-weight:800; color:#111827; }

    .wrap { max-width:980px; margin:0 auto; }
    .card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:18px; box-shadow:0 1px 2px rgba(0,0,0,.03); }

    .progress-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .title { font-weight:800; color:#111827; }
    .sub { color:#6b7280; }
    .chip { background:#111827; color:#fff; font-weight:800; padding:6px 10px; border-radius:9999px; font-size:12px; }
    .bar-wrap { height:10px; background:#E5E7EB; border-radius:9999px; overflow:hidden; }
    .bar { height:10px; background:#9CA3AF; width:0%; }

    .q-card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:20px; box-shadow:0 1px 2px rgba(0,0,0,.03); margin-top:18px; }
    .q-text { font-size:18px; font-weight:800; color:#111827; margin:0 0 12px; }
    .opt { display:flex; align-items:center; gap:10px; border:1px solid #F1F5F9; border-radius:14px; padding:14px; background:#fff; }
    .opt + .opt { margin-top:12px; }
    .opt input { accent-color:#111827; }

    .warn { margin-top:14px; color:#92400E; background:#FFFBEB; border:1px solid #FDE68A; padding:10px 12px; border-radius:12px; display:none; }

    .nav { display:flex; align-items:center; justify-content:space-between; margin-top:16px; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:12px 16px; border-radius:12px; border:1px solid #E5E7EB; background:#fff; color:#111827; font-weight:800; text-decoration:none; }
    .btn-primary { background:#111827; color:#fff; border-color:#111827; }
    .pager { display:flex; gap:8px; }
    .dot { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#F3F4F6; color:#111827; font-weight:800; cursor:pointer; }
    .dot.active { background:#111827; color:#fff; }

    .hidden { display:none; }
  </style>
</head>
<body>
  <main class="main">
    <div class="wrap">
      <div class="header">
        <a class="back" href="{{ route('siswa.kelas.show', ['teaching'=>$teaching, 'tab'=>'tugas']) }}">← Keluar</a>
        <div class="timer">
          ⏱ <span id="countdown">--:--</span>
        </div>
      </div>

      <div class="card">
        <div class="progress-head">
          <div>
            <div class="title">{{ $assignment->title }}</div>
            @php($total = $assignment->questions->count())
            <div class="sub">Soal <span id="curIndex">1</span> dari {{ $total }}</div>
          </div>
          <div class="chip"><span id="answeredCount">0</span> / {{ $total }} dijawab</div>
        </div>
        <div class="bar-wrap"><div id="progressBar" class="bar"></div></div>
      </div>

      <form id="quizForm" action="{{ route('siswa.kuis.submit', [$teaching, $assignment]) }}" method="POST" style="margin-top:14px;">
        @csrf

        @foreach($assignment->questions as $idx => $q)
          <div class="q-card qpane {{ $idx === 0 ? '' : 'hidden' }}" data-idx="{{ $idx }}">
            <div class="q-text">{{ $q->question_text }}</div>
            @foreach($q->choices as $choice)
              <label class="opt">
                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $choice->id }}">
                <span>{{ $choice->choice_text }}</span>
              </label>
            @endforeach
            <div class="warn" role="alert">Anda belum menjawab pertanyaan ini. Silakan pilih salah satu jawaban.</div>
          </div>
        @endforeach

        <div class="nav">
          <button id="prevBtn" type="button" class="btn">← Sebelumnya</button>
          <div class="pager">
            @for($i=0;$i<$total;$i++)
              <div class="dot {{ $i===0 ? 'active' : '' }}" data-go="{{ $i }}">{{ $i+1 }}</div>
            @endfor
          </div>
          <button id="nextBtn" type="button" class="btn btn-primary">Selanjutnya →</button>
        </div>

        @error('answers') <div style="color:#b91c1c; margin-top:8px;">{{ $message }}</div> @enderror
        @error('kuis') <div style="color:#b91c1c; margin-top:8px;">{{ $message }}</div> @enderror
      </form>
    </div>
  </main>

  <script>
    (function(){
      const panes = Array.from(document.querySelectorAll('.qpane'));
      const total = panes.length;
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const dots = Array.from(document.querySelectorAll('.dot'));
      const curIndexEl = document.getElementById('curIndex');
      const answeredEl = document.getElementById('answeredCount');
      const bar = document.getElementById('progressBar');
      const form = document.getElementById('quizForm');

      let cur = 0;

      function answeredCount(){
        return form.querySelectorAll('input[type=radio]:checked').length;
      }

      function updateUI(){
        panes.forEach((p,i)=>p.classList.toggle('hidden', i!==cur));
        dots.forEach((d,i)=>d.classList.toggle('active', i===cur));
        curIndexEl.textContent = cur+1;
        answeredEl.textContent = answeredCount();
        bar.style.width = Math.max(0, Math.min(100, Math.round(((cur)/(Math.max(1,total-1)))*100))) + '%';

        // warning current unanswered
        const radios = panes[cur].querySelectorAll('input[type=radio]');
        const has = Array.from(radios).some(r=>r.checked);
        panes[cur].querySelector('.warn').style.display = has ? 'none' : 'block';

        prevBtn.disabled = cur===0;
        nextBtn.textContent = (cur===total-1) ? 'Kumpulkan →' : 'Selanjutnya →';
      }

      prevBtn.addEventListener('click', ()=>{ if(cur>0){cur--; updateUI();} });
      nextBtn.addEventListener('click', ()=>{
        if(cur===total-1){ form.submit(); return; }
        cur++; updateUI();
      });
      dots.forEach(d=>d.addEventListener('click', ()=>{ cur = parseInt(d.dataset.go||'0',10)||0; updateUI(); }));
      form.addEventListener('change', (e)=>{ if(e.target.matches('input[type=radio]')) updateUI(); });

      // timer (auto submit on deadline)
      const countdownEl = document.getElementById('countdown');
      const endTs = {{ $assignment->end_time ? $assignment->end_time->timestamp : 'null' }};
      if(endTs){
        function tick(){
          const now = Math.floor(Date.now()/1000);
          let left = endTs - now;
          if(left <= 0){ countdownEl.textContent = '00:00'; form.submit(); return; }
          const m = Math.floor(left/60).toString().padStart(2,'0');
          const s = (left%60).toString().padStart(2,'0');
          countdownEl.textContent = m+':'+s;
        }
        tick(); setInterval(tick, 1000);
      } else {
        countdownEl.textContent = '--:--';
      }

      updateUI();
    })();
  </script>
</body>
</html>