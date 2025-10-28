<section class="content-section">
    <div class="content-header">
        <div>
            <h2>Materi</h2>
            <p>Daftar materi pembelajaran untuk kelas ini</p>
        </div>
        <button type="button" class="btn-upload">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
           <a href="{{ route('guru.kelas.materi.create', $teaching) }}" class="btn-upload">
  Upload Materi
</a>
        </button>
    </div>

    <div class="material-list">
        @forelse($teaching->materials as $m)
            <div class="material-item">
                <div class="item-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path></svg>
                </div>
                <div class="item-details">
                    <h3>{{ $m->title }}</h3>
                    <div class="item-meta">
                        <span>
                            @php($label = $m->file_type ?? 'FILE')
                            @if(($m->file_type ?? null) !== 'LINK')
                                @if(!empty($m->file_hex))
                                    @php($ext = strtoupper((string) pathinfo($m->file_name ?? '', PATHINFO_EXTENSION)))
                                    @if(empty($ext) && !empty($m->file_mime))
                                        @php($parts = explode('/', $m->file_mime))
                                        @php($ext = strtoupper($parts[1] ?? ''))
                                    @endif
                                    @if(!empty($ext))
                                        @php($label = $ext)
                                    @endif
                                @elseif(!empty($m->file_path))
                                    @php($ext = strtoupper(pathinfo($m->file_path, PATHINFO_EXTENSION)))
                                    @if(!empty($ext))
                                        @php($label = $ext)
                                    @endif
                                @endif
                            @endif
                            {{ $label }}
                        </span>
                        <span class="separator">•</span>
                        <span>{{ optional($m->uploaded_at)->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="item-actions">
                    @php($detailUrl = ($m->file_type === 'LINK') 
                        ? $m->file_path 
                        : (!empty($m->file_hex) 
                            ? route('guru.kelas.materi.view', [$teaching, $m]) 
                            : asset('storage/'.$m->file_path)))
                    @if($m->file_type === 'LINK' || !empty($m->file_hex) || !empty($m->file_path))
                    <a href="{{ $detailUrl }}" target="_blank" rel="noopener" class="btn-icon" title="Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span style="margin-left:4px">Detail</span>
                    </a>
                    @endif
                    <a href="{{ route('guru.kelas.materi.download', [$teaching, $m]) }}" class="btn-icon" title="Unduh">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </a>
                    <a href="{{ route('guru.kelas.materi.edit', [$teaching, $m]) }}" class="btn-icon" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    </a>
                    <form action="{{ route('guru.kelas.materi.destroy', [$teaching, $m]) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-danger" title="Hapus" onclick="return confirm('Yakin hapus materi ini?')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p>Belum ada materi.</p>
        @endforelse
    </div>
</section>
