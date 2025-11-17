@php(
  $items = $teaching->assignments->sortByDesc(function($a){ return $a->end_time ?? $a->created_at; })
)
@php(
  $grades = $items->map(function($a){ return optional($a->submissions->firstWhere('user_id', auth()->id()))->grade; })
            ->filter(fn($g)=>$g !== null)
)
@php($avg = $grades->isEmpty() ? null : round($grades->avg(), 1))

<style>
  .nilai-container { max-width:860px; margin:0 auto; }
  .h1 { font-size:22px; font-weight:800; color:#111827; margin:0 0 4px; }
  .sub { color:#6b7280; margin:0 0 16px; }

  .avg-card { background:#E5E7EB; border-radius:16px; padding:18px; display:flex; align-items:center; justify-content:space-between; }
  .avg-title { color:#6b7280; font-weight:700; }
  .avg-value { font-size:32px; font-weight:900; color:#111827; }
  .avg-icon { width:56px; height:56px; border-radius:9999px; background:#111827; color:#fff; display:flex; align-items:center; justify-content:center; }

  .divider { border-top:1px solid #E5E7EB; margin:18px 0; }

  .list { display:flex; flex-direction:column; gap:14px; }
  .item { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:16px; display:flex; justify-content:space-between; gap:16px; }
  .left { min-width:0; }
  .chips { display:flex; gap:8px; align-items:center; margin-bottom:6px; color:#6b7280; }
  .chip { background:#F1F5F9; color:#0F172A; border:1px solid #CBD5E1; border-radius:9999px; padding:4px 8px; font-weight:700; font-size:12px; }
  .date { color:#6b7280; font-size:14px; }
  .title { font-size:18px; font-weight:800; color:#111827; margin:0 0 10px; }
  .feedback { background:#F3F4F6; border-radius:12px; padding:12px; color:#111827; }
  .fb-title { font-weight:800; }

  .score { text-align:right; min-width:80px; }
  .score-big { font-size:36px; font-weight:900; color:#111827; line-height:1; }
  .score-sub { color:#6b7280; }
</style>

<div class="nilai-container">
  <div class="h1">Nilai Saya</div>
  <div class="sub">Rekap semua nilai dan feedback untuk kelas ini</div>

  <div class="avg-card">
    <div>
      <div class="avg-title">Rata-rata Nilai</div>
      <div class="avg-value">{{ $avg === null ? '-' : $avg }}</div>
    </div>
    <div class="avg-icon"><i class='bx bx-medal'></i></div>
  </div>

  <div class="divider"></div>

  <div class="list">
    @forelse($items as $a)
      @php($sub = $a->submissions->firstWhere('user_id', auth()->id()))
      <div class="item">
        <div class="left">
          <div class="chips">
            <span class="chip">{{ strtoupper($a->type) === 'KUIS' ? 'Kuis' : 'Tugas' }}</span>
            <span class="date">{{ optional($a->end_time ?? $a->created_at)->format('d/m/Y') }}</span>
          </div>
          <div class="title">{{ $a->title }}</div>

          @if(!empty($sub?->feedback))
            <div class="feedback"><span class="fb-title">Feedback:</span> {{ $sub->feedback }}</div>
          @endif
        </div>
        <div class="score">
          <div class="score-big">{{ $sub && $sub->grade !== null ? number_format($sub->grade, 0) : '-' }}</div>
          <div class="score-sub">/ 100</div>
        </div>
      </div>
    @empty
      <div class="item"><div class="left"><div class="title">Belum ada nilai</div><div class="date">Tugas atau kuis akan tampil di sini setelah dibuat</div></div></div>
    @endforelse
  </div>
</div>