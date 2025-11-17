@php($list = $materials ?? collect())
@php($formatBytes = function ($bytes) { $sizes = ['B','KB','MB','GB','TB']; if ($bytes <= 0) return '0 B'; $i = (int)floor(log($bytes, 1024)); $i = max(0, min($i, count($sizes)-1)); return number_format($bytes / (1024 ** $i), $i ? 1 : 0) . ' ' . $sizes[$i]; })

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
  .section-title { font-size:18px; font-weight:700; color:#0f172a; margin:0 0 4px 0; }
  .section-sub { color:#6b7280; margin:0 0 16px 0; }
  .materials { display:flex; flex-direction:column; gap:12px; }
  .material-item { background:#ffffff; border:1px solid #E5E7EB; border-radius:16px; padding:16px 18px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
  .material-left { display:flex; align-items:flex-start; gap:12px; min-width:0; }
  .doc-icon { flex:0 0 auto; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; background:#F3F4F6; color:#111827; }
  .material-info { min-width:0; }
  .material-title { margin:0 0 4px 0; font-size:16px; font-weight:700; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .material-desc { color:#4b5563; font-size:14px; margin:0 0 6px 0; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
  .material-meta { color:#6b7280; font-size:13px; display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
  .dot::before { content:"•"; margin:0 6px; color:#9CA3AF; }
  .actions { display:flex; gap:10px; }
  .btn { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:12px; text-decoration:none; font-weight:600; border:1px solid #D1D5DB; color:#111827; background:#fff; }
  .btn-primary { background:#111827; color:#fff; border-color:#111827; }
  .btn svg { width:16px; height:16px; }
</style>

<h3 class="section-title">Materi Pembelajaran</h3>
<p class="section-sub">Semua materi yang dibagikan oleh guru</p>

@if($list->isEmpty())
  <div class="materials"><div class="material-item"><div class="material-left"><div class="doc-icon"><i class='bx bx-file-blank'></i></div><div class="material-info"><div class="material-title">Belum ada materi</div><div class="material-meta">Menunggu unggahan dari guru</div></div></div></div></div>
@else
  <div class="materials">
    @foreach($list as $m)
      @php(
        $isLink = is_string($m->file_path ?? null) && (str_starts_with($m->file_path, 'http://') || str_starts_with($m->file_path, 'https://'))
      )
      @php(
        $size = null
      )
      @php(
        $size = (!$isLink && !empty($m->file_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($m->file_path)) ? $formatBytes(\Illuminate\Support\Facades\Storage::disk('public')->size($m->file_path)) : null
      )
      <div class="material-item">
        <div class="material-left">
          <div class="doc-icon"><i class='bx bx-file-blank'></i></div>
          <div class="material-info">
            <div class="material-title">{{ $m->title }}</div>
            @if(!empty($m->description))
              <p class="material-desc">{{ $m->description }}</p>
            @endif
            <div class="material-meta">
              <span>{{ $m->file_type ?? 'FILE' }}</span>
              @if($size)
                <span class="dot"></span><span>{{ $size }}</span>
              @endif
              @if($m->uploaded_at)
                <span class="dot"></span><span>{{ $m->uploaded_at->format('j/n/Y') }}</span>
              @endif
            </div>
          </div>
        </div>
        <div class="actions">
          @if(!empty($m->file_path))
            <a class="btn" href="{{ route('siswa.kelas.materi.view', [$teaching, $m]) }}" target="_blank">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8"/><circle cx="12" cy="12" r="3"/></svg>
              Lihat
            </a>
            <a class="btn btn-primary" href="{{ route('siswa.kelas.materi.download', [$teaching, $m]) }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Unduh
            </a>
          @endif
        </div>
      </div>
    @endforeach
  </div>
@endif
