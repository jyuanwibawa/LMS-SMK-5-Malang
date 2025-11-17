@include('partials._sidebar-siswa')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengumpulan Tugas</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#F7F8FC; margin:0; }
    .main { margin-left:280px; padding:40px; box-sizing:border-box; }

    .header { display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .back { display:inline-flex; align-items:center; gap:8px; text-decoration:none; color:#111827; font-weight:700; }
    .page-title { font-size:28px; font-weight:800; color:#111827; margin:8px 0 2px; }
    .page-sub { color:#6b7280; font-weight:600; }
    .chip { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:9999px; font-weight:700; font-size:12px; border:1px solid #E5E7EB; background:#F9FAFB; color:#111827; }
    .chip-red { background:#FEF2F2; color:#991B1B; border-color:#FECACA; }
    .chip-amber { background:#FFFBEB; color:#92400E; border-color:#FDE68A; }
    .chip-slate { background:#F1F5F9; color:#0F172A; border-color:#CBD5E1; }

    .grid { display:grid; grid-template-columns: 1fr 320px; gap:18px; align-items:start; }
    .card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:20px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
    .section-title { font-size:18px; font-weight:800; color:#0f172a; margin:0 0 12px; }
    .muted { color:#6b7280; font-size:14px; }

    .info-title { color:#6b7280; font-weight:700; margin:0 0 4px; }
    .info-value { font-size:36px; font-weight:900; color:#111827; margin:0 0 8px; }

    /* Upload */
    .upload-card { padding:0; overflow:hidden; }
    .upload-inner { padding:20px; }
    .dropzone { border:2px dashed #D1D5DB; border-radius:16px; padding:28px; text-align:center; background:#FBFBFB; color:#6b7280; cursor:pointer; }
    .dropzone.hover { border-color:#111827; background:#F9FAFB; }
    .dz-icon { font-size:36px; color:#6b7280; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:12px 16px; border-radius:12px; border:1px solid #111827; background:#111827; color:#fff; font-weight:800; text-decoration:none; }
    .btn[disabled] { opacity:.6; cursor:not-allowed; }
    .btn-ghost { background:#fff; color:#111827; border-color:#E5E7EB; }
    .actions { display:flex; gap:12px; }
    .input { border:1px solid #E5E7EB; background:#F9FAFB; border-radius:12px; padding:12px 14px; width:100%; }

    .warn-card { background:#fff; border:1px solid #FECACA; border-radius:16px; padding:16px; color:#991B1B; }
  </style>
</head>
<body>
  <main class="main">
    <a href="{{ route('siswa.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="back">
      <i class='bx bx-arrow-back'></i> Kembali
    </a>

    <div class="header">
      <div>
        <div class="page-title">{{ $assignment->title }}</div>
        <div class="page-sub">
          {{ $teaching->course->name ?? 'Mata pelajaran' }} • {{ $teaching->user->name ?? 'Guru' }}
        </div>
      </div>
      @php(
        $isLate = $assignment->end_time && $assignment->end_time->isPast()
      )
      <span class="chip {{ $isLate ? 'chip-red' : 'chip-amber' }}">{{ $isLate ? 'Terlambat' : 'Belum Dikumpulkan' }}</span>
    </div>

    <div style="height:12px"></div>

    <div class="grid">
      <div class="card">
        <div class="section-title">Deskripsi Tugas</div>
        @if(!empty($assignment->description))
          <div style="white-space:pre-line; color:#111827; line-height:1.65;">{{ $assignment->description }}</div>
        @else
          <div class="muted">Belum ada deskripsi.</div>
        @endif
        <div style="height:18px"></div>

        <div class="section-title">Upload Jawaban</div>
        <div class="muted">Format yang diizinkan: PDF, JPG, PNG (Maks: 10 MB)</div>

        <form id="submit-form" action="{{ route('siswa.tugas.submit.store', [$teaching, $assignment]) }}" method="POST" enctype="multipart/form-data" style="margin-top:12px;">
          @csrf
          <input type="file" id="file-input" name="file" accept=".pdf,.jpg,.jpeg,.png" hidden>

          <div id="dropzone" class="dropzone">
            <div class="dz-icon"><i class='bx bx-upload'></i></div>
            <div style="font-weight:700; color:#111827;">Klik untuk upload file atau drag & drop</div>
            <div class="muted">PDF, JPG, PNG hingga 10 MB</div>
            <div id="file-name" class="muted" style="margin-top:8px; display:none;"></div>
          </div>

          @error('file')
            <div class="muted" style="color:#b91c1c; margin-top:8px;">{{ $message }}</div>
          @enderror

          <div style="height:14px"></div>
          <label class="muted" for="note">Catatan (Opsional)</label>
          <input id="note" class="input" type="text" placeholder="Tambahkan catatan atau keterangan untuk guru..." />

          <div style="height:18px"></div>
          <div class="actions">
            <button id="submit-btn" type="submit" class="btn" disabled>
              <i class='bx bx-upload'></i> Kumpulkan Tugas
            </button>
            <a href="{{ route('siswa.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="btn btn-ghost">Batal</a>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="section-title">Informasi Tugas</div>
        <div class="info-title">Nilai Maksimal</div>
        <div class="info-value">100</div>
        <div class="info-title">Deadline</div>
        <div class="muted">
          <i class='bx bx-calendar'></i> {{ optional($assignment->end_time)->translatedFormat('d F Y') ?: '-' }}<br/>
          <i class='bx bx-time-five'></i> {{ optional($assignment->end_time)->format('H.i') ?: '-' }}
        </div>
        <div class="info-title" style="margin-top:12px;">Status</div>
        <span class="chip {{ $isLate ? 'chip-red' : 'chip-amber' }}">{{ $isLate ? 'Terlambat' : 'Belum Dikumpulkan' }}</span>

        <div style="height:18px"></div>
        <div class="warn-card">
          <div style="font-weight:800; margin-bottom:4px;">Peringatan:</div>
          <div class="muted" style="color:#b91c1c;">Deadline tugas sudah dekat! Segera kumpulkan tugas Anda.</div>
        </div>
      </div>
    </div>
  </main>

  <script>
    (function(){
      const dz = document.getElementById('dropzone');
      const input = document.getElementById('file-input');
      const nameEl = document.getElementById('file-name');
      const submitBtn = document.getElementById('submit-btn');

      function setFile(file){
        if(!file) return;
        const max = 10 * 1024 * 1024; // 10MB
        if(file.size > max){
          alert('Ukuran file melebihi 10 MB');
          input.value = '';
          nameEl.style.display = 'none';
          submitBtn.disabled = true;
          return;
        }
        input.files = new DataTransfer().files; // noop for some browsers
        nameEl.textContent = 'Dipilih: ' + file.name + ' (' + Math.round(file.size/1024) + ' KB)';
        nameEl.style.display = 'block';
        submitBtn.disabled = false;
      }

      dz.addEventListener('click', () => input.click());
      dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('hover'); });
      dz.addEventListener('dragleave', () => dz.classList.remove('hover'));
      dz.addEventListener('drop', e => {
        e.preventDefault(); dz.classList.remove('hover');
        const file = e.dataTransfer.files && e.dataTransfer.files[0];
        if(file){
          // Attach the dropped file to the hidden input
          const dt = new DataTransfer();
          dt.items.add(file);
          input.files = dt.files;
          setFile(file);
        }
      });
      input.addEventListener('change', () => { const f = input.files[0]; setFile(f); });
    })();
  </script>
</body>
</html>