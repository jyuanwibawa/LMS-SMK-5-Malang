@php
    $enrollments = $teaching->schoolClass->enrollments ?? collect();
@endphp

<div class="content-section">
    <div style="
        background-color: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        padding: 24px 28px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    ">
        <div class="content-header" style="margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #111827;">
                    Peserta Kelas
                </h2>
                <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #6B7281;">
                    Daftar siswa terdaftar
                </p>
            </div>
        </div>

        @if($enrollments->isEmpty())
            <div style="background-color: #F9FAFB; border-radius: 12px; padding: 16px; font-size: 0.95rem; color: #6B7281;">
                Belum ada siswa yang terdaftar pada kelas ini.
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="text-align: left; color: #6B7281; border-bottom: 1px solid #E5E7EB;">
                            <th style="padding: 10px 0;">NISN</th>
                            <th style="padding: 10px 0;">Nama</th>
                            <th style="padding: 10px 0;">Email</th>
                            <th style="padding: 10px 0; text-align: right;">Rata-rata Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            @php
                                $user = $enrollment->user;
                                // Kumpulkan semua submission milik user ini dari semua tugas di teaching ini
                                $submissions = $teaching->assignments
                                    ->flatMap->submissions
                                    ->where('user_id', $user?->id)
                                    ->whereNotNull('grade');

                                $avgGrade = $submissions->avg('grade');
                            @endphp
                            <tr style="border-bottom: 1px solid #F3F4F6;">
                                <td style="padding: 10px 0; color: #111827;">
                                    {{ $user?->identity_number ?? '-' }}
                                </td>
                                <td style="padding: 10px 0; color: #111827; font-weight: 600;">
                                    {{ $user?->name ?? '-' }}
                                </td>
                                <td style="padding: 10px 0; color: #6B7281;">
                                    {{ $user?->email ?? '-' }}
                                </td>
                                <td style="padding: 10px 0; text-align: right;">
                                    @if(!is_null($avgGrade))
                                        <span style="
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            min-width: 40px;
                                            padding: 4px 10px;
                                            border-radius: 9999px;
                                            border: 1px solid #E5E7EB;
                                            background-color: #F9FAFB;
                                            font-size: 0.85rem;
                                            color: #111827;
                                            font-weight: 500;
                                        ">
                                            {{ round($avgGrade) }}
                                        </span>
                                    @else
                                        <span style="font-size: 0.85rem; color: #9CA3AF;">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>