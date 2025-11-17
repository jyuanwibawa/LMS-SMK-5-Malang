@include('partials._sidebar-guru')

<main class="main-content" style="margin-left:280px;padding:40px;box-sizing:border-box;">
  <div class="container" style="max-width:960px;margin:0 auto;">
    <header class="page-header" style="margin-bottom:24px;">
      <a href="{{ route('guru.kelas.show', $teaching) }}" class="back-link" style="text-decoration:none;color:#4B5563;font-weight:500;display:inline-block;margin-bottom:12px;">← Kembali</a>
      <h1 style="margin:0 0 6px 0;">Buat Kuis (Pilihan Ganda)</h1>
      <p class="subtitle" style="color:#6B7280;margin:0;">Tambahkan pertanyaan dan pilihan jawaban</p>
    </header>

    <style>
      .card { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:24px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
      .form-group { margin-bottom:16px; }
      .form-label { display:block; font-weight:600; margin-bottom:8px; }
      .form-input, .form-textarea, .form-select { width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:8px; }
      .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
      .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:8px; cursor:pointer; text-decoration:none; font-weight:600; }
      .btn-primary { background:#111827; color:#fff; border:none; }
      .btn-secondary { background:#fff; color:#374151; border:1px solid #D1D5DB; }
      .btn-danger { background:#fff; color:#B91C1C; border:1px solid #FCA5A5; }
      .q-item { border:1px solid #E5E7EB; border-radius:10px; padding:16px; margin-bottom:16px; background:#FAFAFA; }
      .choice-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
      .choice-row input[type="text"] { flex:1; }
      .muted { color:#6B7280; font-size:13px; }
      .actions { display:flex; gap:10px; }
    </style>

    <section class="card">
      <form method="POST" action="{{ route('guru.kelas.kuis.store', $teaching) }}" id="quizForm">
        @csrf
        <div class="form-group">
          <label class="form-label" for="title">Judul</label>
          <input class="form-input" type="text" id="title" name="title" value="{{ old('title') }}" required>
          @error('title')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="description">Deskripsi</label>
          <textarea class="form-textarea" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label" for="start_time">Mulai</label>
            <input class="form-input" type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}">
          </div>
          <div class="form-group">
            <label class="form-label" for="end_time">Selesai (Deadline)</label>
            <input class="form-input" type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}">
          </div>
        </div>

        <div class="form-group" style="margin-top:12px;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <label class="form-label">Pertanyaan</label>
            <button type="button" class="btn btn-secondary" onclick="addQuestion()">+ Tambah Pertanyaan</button>
          </div>
          <div id="questionsContainer"></div>
          @error('questions')<div style="color:#DC2626;margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div class="actions" style="margin-top:20px;">
          <a href="{{ route('guru.kelas.show', $teaching) }}" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan Kuis</button>
        </div>
      </form>
    </section>
  </div>
</main>

<script>
  const qContainer = document.getElementById('questionsContainer');

  function addQuestion(prefill = null) {
    const qIndex = qContainer.children.length;
    const wrapper = document.createElement('div');
    wrapper.className = 'q-item';

    wrapper.innerHTML = `
      <div class="form-group">
        <label class="form-label">Teks Pertanyaan</label>
        <textarea class="form-textarea" name="questions[${qIndex}][question_text]" rows="2" required>${prefill?.question_text || ''}</textarea>
      </div>
      <div class="muted" style="margin-bottom:8px;">Tentukan pilihan dan jawaban benar</div>
      <div class="choices" id="choices-${qIndex}"></div>
      <div class="actions" style="margin-top:8px;">
        <button type="button" class="btn btn-secondary" onclick="addChoice(${qIndex})">+ Tambah Pilihan</button>
        <button type="button" class="btn btn-danger" onclick="this.closest('.q-item').remove(); renumberQuestions();">Hapus Pertanyaan</button>
      </div>
    `;

    qContainer.appendChild(wrapper);

    addChoice(qIndex, prefill?.choices?.[0]);
    addChoice(qIndex, prefill?.choices?.[1]);
    if (prefill?.choices && prefill.choices.length > 2) {
      for (let i = 2; i < prefill.choices.length; i++) addChoice(qIndex, prefill.choices[i]);
    }
    if (typeof prefill?.correct_index === 'number') {
      const radios = wrapper.querySelectorAll('input[type="radio"][name="questions['+qIndex+'][correct_index]"]');
      if (radios[prefill.correct_index]) radios[prefill.correct_index].checked = true;
    } else {
      const firstRadio = wrapper.querySelector('input[type="radio"][name="questions['+qIndex+'][correct_index]"]');
      if (firstRadio) firstRadio.checked = true;
    }
  }

  function addChoice(qIndex, prefill = null) {
    const target = document.getElementById('choices-' + qIndex);
    const cIndex = target.children.length;
    const row = document.createElement('div');
    row.className = 'choice-row';
    row.innerHTML = `
      <input type="radio" name="questions[${qIndex}][correct_index]" value="${cIndex}">
      <input type="text" class="form-input" name="questions[${qIndex}][choices][${cIndex}][choice_text]" placeholder="Teks pilihan" value="${prefill?.choice_text || ''}" required>
      <button type="button" class="btn btn-danger" onclick="this.parentElement.remove(); renumberChoices(${qIndex});">Hapus</button>
    `;
    target.appendChild(row);
  }

  function renumberQuestions() {
    [...qContainer.children].forEach((qEl, newQIndex) => {
      qEl.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/questions\[\d+\]/g, `questions[${newQIndex}]`);
      });
      qEl.querySelector('#choices-' + newQIndex) || (qEl.querySelector('[id^="choices-"]').id = 'choices-' + newQIndex);
      renumberChoices(newQIndex);
    });
  }

  function renumberChoices(qIndex) {
    const target = document.getElementById('choices-' + qIndex);
    [...target.children].forEach((row, newCIndex) => {
      const radio = row.querySelector('input[type="radio"]');
      radio.value = newCIndex;
      radio.name = `questions[${qIndex}][correct_index]`;
      const input = row.querySelector('input[type="text"]');
      input.name = `questions[${qIndex}][choices][${newCIndex}][choice_text]`;
    });
  }

  addQuestion();
</script>
