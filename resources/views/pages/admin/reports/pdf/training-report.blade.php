<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Training</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 20px; margin-bottom: 8px; color: #555; }
        .meta { font-size: 11px; color: #777; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background: #f5f5f5; font-weight: 600; }
        .summary-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
        .summary-item { background: #f9f9f9; border: 1px solid #eee; padding: 8px 12px; min-width: 120px; }
        .summary-label { font-size: 10px; color: #777; }
        .summary-value { font-size: 16px; font-weight: bold; }
        .filters { font-size: 11px; color: #555; margin-bottom: 12px; }
        .filters span { margin-right: 12px; }
    </style>
</head>
<body>
    <h1>Laporan Training</h1>
    <div class="meta">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</div>

    @if(!empty($filters))
        <div class="filters">
            <strong>Filter:</strong>
            @foreach($filters as $key => $value)
                <span>{{ $key }}: {{ $value }}</span>
            @endforeach
        </div>
    @endif

    <h2>Ringkasan</h2>
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-label">Total Peserta</div>
            <div class="summary-value">{{ $summary['total_peserta'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Training</div>
            <div class="summary-value">{{ $summary['total_training'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Lulus</div>
            <div class="summary-value">{{ $summary['total_lulus'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Tidak Lulus</div>
            <div class="summary-value">{{ $summary['total_tidak_lulus'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Menunggu</div>
            <div class="summary-value">{{ $summary['total_menunggu'] }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Kelulusan</div>
            <div class="summary-value">{{ $summary['persentase_kelulusan'] }}%</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Rata Pre-Test</div>
            <div class="summary-value">{{ $summary['rata_pre_test'] !== null ? number_format((float) $summary['rata_pre_test'], 2) : '-' }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Rata Post-Test</div>
            <div class="summary-value">{{ $summary['rata_post_test'] !== null ? number_format((float) $summary['rata_post_test'], 2) : '-' }}</div>
        </div>
    </div>

    <h2>Data Peserta</h2>
    @if($participants->isEmpty())
        <p>Tidak ada data.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>Training</th>
                    <th>Status</th>
                    <th>Pre-Test</th>
                    <th>Post-Test</th>
                    <th>Nilai Akhir</th>
                    <th>Kelulusan</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $i => $p)
                    @php
                        $progressLabel = match($p->progress_status) {
                            'not_started' => 'Belum Mulai',
                            'in_progress' => 'Berjalan',
                            'waiting_grading' => 'Menunggu Nilai',
                            'passed' => 'Lulus',
                            'remedial' => 'Remedial',
                            'failed' => 'Gagal',
                            default => $p->progress_status,
                        };
                        $passFail = match($p->progress_status) {
                            'passed' => 'Lulus',
                            'failed', 'remedial' => 'Tidak Lulus',
                            default => '-',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->employee?->name ?? '-' }}</td>
                        <td>{{ $p->employee?->department?->name ?? '-' }}</td>
                        <td>{{ $p->employee?->position?->name ?? '-' }}</td>
                        <td>{{ $p->training?->title ?? '-' }}</td>
                        <td>{{ $progressLabel }}</td>
                        <td>{{ $p->pre_test_score !== null ? number_format((float) $p->pre_test_score, 2) : '-' }}</td>
                        <td>{{ $p->post_test_score !== null ? number_format((float) $p->post_test_score, 2) : '-' }}</td>
                        <td>{{ $p->final_score !== null ? number_format((float) $p->final_score, 2) : '-' }}</td>
                        <td>{{ $passFail }}</td>
                        <td>{{ $p->started_at?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $p->completed_at?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
