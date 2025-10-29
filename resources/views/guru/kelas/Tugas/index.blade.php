<style>
    .tugaskuis-container { max-width: 800px; margin: 20px auto; }
    .tugaskuis-section { margin-bottom: 40px; }
    .tugaskuis-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .tugaskuis-header h2 { font-size: 24px; font-weight: 600; margin: 0; }
    .tugaskuis-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px; font-size: 14px; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.2s ease, box-shadow 0.2s ease; }
    .tugaskuis-btn-primary { background-color: #111; color: #ffffff; }
    .tugaskuis-btn-primary:hover { background-color: #333; }
    .tugaskuis-btn-secondary { background-color: #ffffff; color: #111; border: 1px solid #dee2e6; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04); }
    .tugaskuis-btn-secondary:hover { background-color: #f8f9fa; }
    .tugaskuis-btn .icon-plus { font-size: 20px; line-height: 1; font-weight: 400; }
    .tugaskuis-btn .icon-svg { width: 16px; height: 16px; }
    .tugaskuis-card-list { display: grid; gap: 16px; }
    .tugaskuis-card { background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03); display: flex; justify-content: space-between; align-items: center; }
    .tugaskuis-card h3 { font-size: 18px; font-weight: 600; margin: 0 0 8px 0; }
    .tugaskuis-meta { display: flex; flex-wrap: wrap; gap: 4px 0; font-size: 14px; color: #6c757d; }
    .tugaskuis-meta span:not(:last-child)::after { content: "\2022"; margin: 0 8px; color: #adb5bd; }
</style>

@php(
    $tugas = $teaching->assignments->filter(function($a){ return strtolower($a->type ?? '') !== 'quiz'; })
)
@php(
    $kuis = $teaching->assignments->filter(function($a){ return strtolower($a->type ?? '') === 'quiz'; })
)

<div class="tugaskuis-container">
    <section class="tugaskuis-section">
        <header class="tugaskuis-header">
            <h2>Tugas</h2>
            <a href="{{ route('guru.kelas.tugas.create', $teaching) }}" class="tugaskuis-btn tugaskuis-btn-primary" style="text-decoration:none;">
                <span class="icon-plus">+</span>
                Buat Tugas
            </a>
        </header>

        @if($tugas->isEmpty())
            <p style="color:#6c757d;">Belum ada tugas.</p>
        @else
        <div class="tugaskuis-card-list">
            @foreach($tugas as $a)
            <article class="tugaskuis-card">
                <div class="tugaskuis-card-content">
                    <h3>{{ $a->title }}</h3>
                    <div class="tugaskuis-meta">
                        <span>Deadline: {{ optional($a->end_time)->format('d/m/Y H:i') ?? '-' }}</span>
                        <span>{{ $a->submissions->count() }} terkumpul</span>
                    </div>
                </div>
                <div class="tugaskuis-card-actions" style="display:flex;gap:8px;">
                    <a href="{{ route('guru.kelas.tugas.nilai', [$teaching, $a]) }}" class="tugaskuis-btn tugaskuis-btn-primary" style="text-decoration:none;">Nilai Tugas</a>
                    <a href="{{ route('guru.kelas.tugas.edit', [$teaching, $a]) }}" class="tugaskuis-btn tugaskuis-btn-secondary" style="text-decoration:none;">Edit</a>
                    <form action="{{ route('guru.kelas.tugas.destroy', [$teaching, $a]) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="tugaskuis-btn tugaskuis-btn-secondary">Hapus</button>
                    </form>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>

    <section class="tugaskuis-section">
        <header class="tugaskuis-header">
            <h2>Kuis</h2>
            <button class="tugaskuis-btn tugaskuis-btn-primary">
                <span class="icon-plus">+</span>
                Buat Kuis
            </button>
        </header>

        @if($kuis->isEmpty())
            <p style="color:#6c757d;">Belum ada kuis.</p>
        @else
        <div class="tugaskuis-card-list">
            @foreach($kuis as $q)
            <article class="tugaskuis-card">
                <div class="tugaskuis-card-content">
                    <h3>{{ $q->title }}</h3>
                    <div class="tugaskuis-meta">
                        <span>{{ optional($q->start_time)->format('d/m/Y H:i') ?? '-' }}</span>
                        <span>{{ $q->submissions->count() }} peserta</span>
                    </div>
                </div>
                <div class="tugaskuis-card-actions" style="display:flex;gap:8px;">
                    <a href="{{ route('guru.kelas.tugas.nilai', [$teaching, $q]) }}" class="tugaskuis-btn tugaskuis-btn-primary" style="text-decoration:none;">Nilai Tugas</a>
                    <a href="{{ route('guru.kelas.tugas.edit', [$teaching, $q]) }}" class="tugaskuis-btn tugaskuis-btn-secondary" style="text-decoration:none;">Edit</a>
                    <form action="{{ route('guru.kelas.tugas.destroy', [$teaching, $q]) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="tugaskuis-btn tugaskuis-btn-secondary">Hapus</button>
                    </form>
                    <button class="tugaskuis-btn tugaskuis-btn-secondary">
                        <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        Lihat Hasil
                    </button>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>
</div>
