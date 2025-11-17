@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tugas dan Kuis</h4>
                    <a href="{{ route('siswa.kelas.show', $teaching) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Kelas
                    </a>
                </div>

                <div class="card-body">
                    @if($assignments->isEmpty() && $quizzes->isEmpty())
                        <div class="alert alert-info">Belum ada tugas atau kuis yang tersedia.</div>
                    @else
                        <!-- Tugas Section -->
                        @if(!$assignments->isEmpty())
                            <div class="mb-5">
                                <h5 class="mb-3 border-bottom pb-2">Daftar Tugas</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Batas Waktu</th>
                                                <th>Status</th>
                                                <th>Nilai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assignments as $assignment)
                                                @php($submission = $assignment->submissions->first())
                                                <tr>
                                                    <td>{{ $assignment->title }}</td>
                                                    <td>{{ $assignment->due_date->format('d M Y H:i') }}</td>
                                                    <td>
                                                        @if($submission)
                                                            <span class="badge bg-success">Terkumpul</span>
                                                        @elseif($assignment->due_date->isPast())
                                                            <span class="badge bg-danger">Terlambat</span>
                                                        @else
                                                            <span class="badge bg-warning">Belum Dikerjakan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($submission && $submission->grade)
                                                            {{ $submission->grade }}/100
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('siswa.tugas.show', [$teaching, $assignment]) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            Lihat Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Kuis Section -->
                        @if(!$quizzes->isEmpty())
                            <div class="mb-3">
                                <h5 class="mb-3 border-bottom pb-2">Daftar Kuis</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Waktu Mulai</th>
                                                <th>Waktu Selesai</th>
                                                <th>Status</th>
                                                <th>Nilai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($quizzes as $quiz)
                                                @php($attempt = $quiz->attempts->first())
                                                <tr>
                                                    <td>{{ $quiz->title }}</td>
                                                    <td>{{ $quiz->start_time->format('d M Y H:i') }}</td>
                                                    <td>{{ $quiz->end_time->format('d M Y H:i') }}</td>
                                                    <td>
                                                        @if($attempt)
                                                            <span class="badge bg-success">Selesai</span>
                                                        @elseif($quiz->end_time->isPast())
                                                            <span class="badge bg-secondary">Kadaluarsa</span>
                                                        @elseif($quiz->start_time->isFuture())
                                                            <span class="badge bg-info">Belum Dimulai</span>
                                                        @else
                                                            <span class="badge bg-warning">Belum Dikerjakan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($attempt && $attempt->score)
                                                            {{ $attempt->score }}/100
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($quiz->end_time->isFuture() && $quiz->start_time->isPast() && !$attempt)
                                                            <a href="{{ route('siswa.kuis.mulai', [$teaching, $quiz]) }}" 
                                                               class="btn btn-sm btn-success">
                                                                Kerjakan
                                                            </a>
                                                        @elseif($attempt)
                                                            <a href="{{ route('siswa.kuis.hasil', [$teaching, $quiz, $attempt]) }}" 
                                                               class="btn btn-sm btn-info">
                                                                Lihat Hasil
                                                            </a>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                Tidak Tersedia
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection