@include('partials._sidebar-guru')

<main class="main-content" style="margin-left:280px;padding:40px;box-sizing:border-box;">
  <div class="container" style="max-width:800px;margin:0 auto;">
    <header class="page-header" style="margin-bottom:24px;">
      <a href="{{ route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="back-link" style="text-decoration:none;color:#4B5563;font-weight:500;display:inline-block;margin-bottom:12px;">← Kembali</a>
      <h1 style="margin:0 0 6px 0;">Buat Tugas/Kuis</h1>
      <p style="color:#6B7281;margin:0;">Tambahkan tugas atau kuis untuk kelas ini</p>
    </header>

    <section class="form-card" style="background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:24px;box-shadow:0 1px 2px rgba(0,0,0,0.03);">
      <form action="{{ route('guru.kelas.tugas.store', $teaching) }}" method="POST">
        @csrf

        <div class="form-group" style="margin-bottom:16px;">
          <label for="title" style="display:block;font-weight:600;margin-bottom:8px;">Judul</label>
          <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}" required style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;">
          @error('title')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom:16px;">
          <label for="description" style="display:block;font-weight:600;margin-bottom:8px;">Deskripsi</label>
          <textarea id="description" name="description" rows="4" class="form-input" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;">{{ old('description') }}</textarea>
          @error('description')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
        </div>


        <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group">
            <label for="start_time" style="display:block;font-weight:600;margin-bottom:8px;">Mulai</label>
            <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" class="form-input" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;">
            @error('start_time')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label for="end_time" style="display:block;font-weight:600;margin-bottom:8px;">Selesai (Deadline)</label>
            <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" class="form-input" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;">
            @error('end_time')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-actions" style="margin-top:20px;display:flex;gap:12px;">
          <a href="{{ route('guru.kelas.show', ['teaching' => $teaching, 'tab' => 'tugas']) }}" class="btn btn-secondary" style="text-decoration:none;background:#fff;color:#374151;border:1px solid #D1D5DB;padding:10px 16px;border-radius:8px;">Batal</a>
          <button type="submit" class="btn btn-primary" style="background:#111827;color:#fff;border:none;padding:10px 16px;border-radius:8px;">Simpan</button>
        </div>
      </form>
    </section>
  </div>
</main>
