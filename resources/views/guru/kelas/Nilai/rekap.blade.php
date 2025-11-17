@php($enrollments = $teaching->schoolClass->enrollments ?? collect())
@php($assignments = $teaching->assignments ?? collect())
@php($students = $enrollments->map(fn($e) => $e->user)->filter()->values())
@php($workItems = $assignments->values())
<style>
  .grade-card { background:#fff; border-radius:18px; border:1px solid #E5E7EB; padding:18px 20px; margin-top:16px; }
  .grade-title { font-size:18px; font-weight:800; margin:0 0 4px; color:#111827; }
  .grade-sub { font-size:13px; color:#6B7280; margin:0 0 16px; }
  .grade-table { width:100%; border-collapse:collapse; font-size:14px; }
  .grade-table th { text-align:left; padding:10px 12px; border-bottom:1px solid #E5E7EB; font-weight:600; color:#6B7280; }
  .grade-table td { padding:10px 12px; border-bottom:1px solid #F3F4F6; color:#111827; }
  .grade-table tbody tr:last-child td { border-bottom:none; }
  .badge-avg { display:inline-flex; align-items:center; justify-content:center; min-width:34px; padding:4px 8px; border-radius:999px; background:#111827; color:#fff; font-weight:700; font-size:13px; }
  .name-strong { font-weight:700; }
</style>

<section class="grade-card">
  <h2 class="grade-title">Rekap Nilai</h2>
  <p class="grade-sub">Tabel nilai seluruh siswa</p>

  @if($students->isEmpty())
    <p class="grade-sub">Belum ada siswa terdaftar di kelas ini.</p>
  @else
    <div style="overflow-x:auto;">
      <table class="grade-table">
        <thead>
          <tr>
            <th style="width:120px;">NISN</th>
            <th style="min-width:160px;">Nama</th>
            @foreach($workItems as $work)
              <th>{{ $work->title }}</th>
            @endforeach
            <th style="width:110px;">Rata-rata</th>
          </tr>
        </thead>
        <tbody>
          @foreach($students as $stu)
            @php($grades = [])
            <tr>
              <td>{{ $stu->identity_number }}</td>
              <td class="name-strong">{{ $stu->name }}</td>

              @foreach($workItems as $work)
                @php($submission = $work->submissions->firstWhere('user_id', $stu->id))
                @php($g = $submission && !is_null($submission->grade) ? (float)$submission->grade : null)
                <td>
                  @if($g !== null)
                    @php($grades[] = $g)
                    {{ rtrim(rtrim(number_format($g, 2, '.', ''), '0'), '.') }}
                  @else
                    -
                  @endif
                </td>
              @endforeach

              @php($avg = count($grades) ? array_sum($grades)/count($grades) : null)
              <td>
                @if($avg !== null)
                  <span class="badge-avg">{{ round($avg) }}</span>
                @else
                  <span class="grade-sub">-</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</section>