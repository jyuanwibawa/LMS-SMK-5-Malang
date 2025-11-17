@php
  // Normalize inputs and build a single list for rendering
  $assignments = isset($assignments) && $assignments ? $assignments : collect();
  $quizzes = isset($quizzes) && $quizzes ? $quizzes : collect();

  $items = $assignments->map(function($a){ $a->__kind = 'TUGAS'; return $a; })
    ->merge($quizzes->map(function($q){ $q->__kind = 'KUIS'; return $q; }))
    ->sortByDesc(function($x){ return $x->start_time ?? $x->created_at; })
    ->values();
@endphp

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
  .work-section-title { font-size:18px; font-weight:800; color:#0f172a; margin:0; }
  .work-section-sub { color:#6b7280; margin:4px 0 16px; }
  .work-list { display:flex; flex-direction:column; gap:16px; }
  .work-card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:18px; display:flex; align-items:flex-start; justify-content:space-between; gap:16px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
  .work-left { min-width:0; }
  .chips { display:flex; gap:8px; margin-bottom:8px; }
  .chip { display:inline-flex; align-items:center; padding:6px 10px; font-weight:700; font-size:12px; border-radius:9999px; border:1px solid #E5E7EB; background:#F9FAFB; color:#111827; }
  .chip-green { background:#ECFDF5; color:#065F46; border-color:#A7F3D0; }
  .chip-red { background:#FEF2F2; color:#991B1B; border-color:#FECACA; }
  .chip-amber { background:#FFFBEB; color:#92400E; border-color:#FDE68A; }
  .chip-slate { background:#F1F5F9; color:#0F172A; border-color:#CBD5E1; }
  .work-title { font-size:18px; font-weight:800; margin:0 0 4px; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .work-desc { color:#4b5563; margin:0 0 10px; }
  .work-meta { color:#6b7280; font-size:14px; display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
  .work-meta .dot::before { content:"•"; margin:0 6px; color:#9CA3AF; }
  .work-action { align-self:center; }
  .btn-action { display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:9999px; border:1px solid #111827; background:#111827; color:#fff; text-decoration:none; font-weight:700; }
  .btn-action[disabled] { opacity:.6; cursor:not-allowed; }
  .btn-ghost { background:#fff; color:#111827; border-color:#E5E7EB; }
</style>

<h4 class="work-section-title">Tugas & Kuis</h4>
<p class="work-section-sub">Daftar tugas dan kuis beserta statusnya</p>

@if($items->isEmpty())
  <div class="work-list">
    <div class="work-card"><div class="work-left"><div class="work-title">Belum ada tugas atau kuis</div><p class="work-desc">Menunggu penugasan dari guru</p></div></div>
  </div>
@else
  <div class="work-list">
    @foreach($items as $item)
      @php($submission = $item->submissions->first())
      @php(
        $isQuiz = ($item->__kind ?? $item->type) === 'KUIS'
      )
      @php(
        $statusChip = $submission ? 'chip-slate' : (($item->end_time && $item->end_time->isPast()) ? 'chip-red' : 'chip-amber')
      )
      @php(
        $statusText = $submission ? 'Selesai' : (($item->end_time && $item->end_time->isPast()) ? 'Kadaluarsa' : 'Belum Dikerjakan')
      )
      <div class="work-card">
        <div class="work-left">
          <div class="chips">
            <span class="chip">{{ $isQuiz ? 'Kuis' : 'Tugas' }}</span>
            <span class="chip {{ $statusChip }}">{{ $statusText }}</span>
            @if($submission && !is_null($submission->grade))
              <span class="chip chip-green">Dinilai: {{ number_format($submission->grade, 0) }}</span>
            @endif
          </div>
          <div class="work-title">{{ $item->title }}</div>
          @if(!empty($item->description))
            <p class="work-desc">{{ $item->description }}</p>
          @endif
          <div class="work-meta">
            <span><i class='bx bx-time-five'></i> Deadline: {{ optional($item->end_time)->format('d/m/Y') ?: '-' }}</span>
          </div>
        </div>
        <div class="work-action">
          @if(!$submission)
@if($isQuiz)
  <a class="btn-action" href="{{ route('siswa.kuis.start', [$teaching, $item]) }}">Mulai Kuis</a>
@else
  <a class="btn-action" href="{{ route('siswa.tugas.submit.create', [$teaching, $item]) }}">Kumpulkan</a>
@endif
          @else
            <button class="btn-action btn-ghost" disabled>
              <i class='bx bx-check-circle'></i> Selesai
            </button>
          @endif
        </div>
      </div>
    @endforeach
  </div>
@endif