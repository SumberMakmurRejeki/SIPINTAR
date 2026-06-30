<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class TrainingReportController extends Controller
{
    public function index(Request $request): View
    {
        [$query, $summary] = $this->buildQuery($request);

        $participants = $query
            ->latest('training_participants.created_at')
            ->paginate(10)
            ->withQueryString();

        $trainings = Training::orderBy('title')->get();
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('status', 'active')->orderBy('name')->get();

        return view('pages::admin.reports.training.index', compact(
            'participants',
            'trainings',
            'departments',
            'positions',
            'summary',
        ));
    }

    public function exportPdf(Request $request)
    {
        [$query, $summary] = $this->buildQuery($request);

        $participants = $query->latest('training_participants.created_at')->get();
        $filters = $this->activeFilters($request);

        $pdf = Pdf::loadView('pages::admin.reports.pdf.training-report', compact(
            'participants',
            'summary',
            'filters',
        ));

        return $pdf->download('laporan-training-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        [$query, $summary] = $this->buildQuery($request);

        $participants = $query->latest('training_participants.created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-training-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($participants) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Nama Karyawan',
                'Departemen',
                'Jabatan',
                'Judul Training',
                'Status Progress',
                'Nilai Pre-Test',
                'Nilai Post-Test',
                'Nilai Akhir',
                'Status Kelulusan',
                'Tanggal Mulai',
                'Tanggal Selesai',
            ]);

            foreach ($participants as $p) {
                fputcsv($handle, [
                    $p->employee?->name ?? '-',
                    $p->employee?->department?->name ?? '-',
                    $p->employee?->position?->name ?? '-',
                    $p->training?->title ?? '-',
                    $this->progressLabel($p->progress_status),
                    $p->pre_test_score !== null ? number_format((float) $p->pre_test_score, 2) : '-',
                    $p->post_test_score !== null ? number_format((float) $p->post_test_score, 2) : '-',
                    $p->final_score !== null ? number_format((float) $p->final_score, 2) : '-',
                    $this->passFailLabel($p),
                    $p->started_at?->format('d/m/Y') ?? '-',
                    $p->completed_at?->format('d/m/Y') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * @return array{0: Builder, 1: array<string, mixed>}
     */
    private function buildQuery(Request $request): array
    {
        $query = TrainingParticipant::with([
            'training',
            'employee.user',
            'employee.department',
            'employee.position',
        ]);

        // Default: bulan berjalan
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        // Filter by date range using completed_at or created_at
        $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('completed_at', [$startDate, $endDate])
                ->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->whereNull('completed_at')
                        ->whereBetween('created_at', [$startDate, $endDate]);
                });
        });

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('department_id', $request->department_id));
        }

        if ($request->filled('position_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('position_id', $request->position_id));
        }

        if ($request->filled('status')) {
            $query->where('progress_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search): void {
                $q->whereHas('employee', fn ($eq) => $eq->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('training', fn ($tq) => $tq->where('title', 'like', '%'.$search.'%'));
            });
        }

        // Summary counts (clone before pagination)
        $summary = [
            'total_peserta' => (clone $query)->count(),
            'total_training' => (clone $query)->distinct('training_id')->count('training_id'),
            'total_lulus' => (clone $query)->where('progress_status', 'passed')->count(),
            'total_tidak_lulus' => (clone $query)->whereIn('progress_status', ['failed', 'remedial'])->count(),
            'total_menunggu' => (clone $query)->where('progress_status', 'waiting_grading')->count(),
            'persentase_kelulusan' => 0,
            'rata_pre_test' => (clone $query)->whereNotNull('pre_test_score')->avg('pre_test_score'),
            'rata_post_test' => (clone $query)->whereNotNull('post_test_score')->avg('post_test_score'),
        ];

        $summary['persentase_kelulusan'] = $summary['total_peserta'] > 0
            ? round(($summary['total_lulus'] / $summary['total_peserta']) * 100, 1)
            : 0;

        return [$query, $summary];
    }

    private function activeFilters(Request $request): array
    {
        $filters = [];

        if ($request->filled('training_id')) {
            $training = Training::find($request->training_id);
            $filters['Training'] = $training?->title ?? $request->training_id;
        }

        if ($request->filled('department_id')) {
            $dept = Department::find($request->department_id);
            $filters['Departemen'] = $dept?->name ?? $request->department_id;
        }

        if ($request->filled('position_id')) {
            $pos = Position::find($request->position_id);
            $filters['Jabatan'] = $pos?->name ?? $request->position_id;
        }

        if ($request->filled('status')) {
            $filters['Status'] = $this->progressLabel($request->status);
        }

        if ($request->filled('start_date')) {
            $filters['Tanggal Mulai'] = $request->start_date;
        }

        if ($request->filled('end_date')) {
            $filters['Tanggal Selesai'] = $request->end_date;
        }

        if ($request->filled('search')) {
            $filters['Pencarian'] = $request->search;
        }

        return $filters;
    }

    private function progressLabel(string $status): string
    {
        return match ($status) {
            'not_started' => 'Belum Mulai',
            'in_progress' => 'Sedang Berjalan',
            'waiting_grading' => 'Menunggu Nilai',
            'passed' => 'Lulus',
            'remedial' => 'Remedial',
            'failed' => 'Gagal',
            default => $status,
        };
    }

    private function passFailLabel(TrainingParticipant $p): string
    {
        return match ($p->progress_status) {
            'passed' => 'Lulus',
            'failed', 'remedial' => 'Tidak Lulus',
            default => '-',
        };
    }
}
