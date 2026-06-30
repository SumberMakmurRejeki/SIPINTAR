<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrainingParticipantRequest;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrainingParticipantController extends Controller
{
    public function store(StoreTrainingParticipantRequest $request, Training $training): RedirectResponse
    {
        $employeeIds = $this->resolveEmployeeIds($request);

        $existing = TrainingParticipant::withTrashed()
            ->where('training_id', $training->id)
            ->whereIn('employee_id', $employeeIds)
            ->pluck('employee_id')
            ->toArray();

        $newIds = array_diff($employeeIds, $existing);
        $skipped = count($existing);

        if (empty($newIds)) {
            return redirect(route('admin.training.show', $training).'?tab=peserta')->with('error', 'Semua karyawan yang dipilih sudah menjadi peserta training ini.');
        }

        $inserts = array_map(function ($employeeId) use ($training) {
            return [
                'training_id' => $training->id,
                'employee_id' => $employeeId,
                'progress_status' => 'not_started',
                'pre_test_status' => 'not_started',
                'material_status' => 'locked',
                'post_test_status' => 'locked',
                'grading_status' => 'none',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $newIds);

        TrainingParticipant::insert($inserts);

        $msg = count($newIds).' peserta berhasil ditambahkan.';
        if ($skipped > 0) {
            $msg .= ' '.$skipped.' dilewati karena sudah menjadi peserta.';
        }

        return redirect(route('admin.training.show', $training).'?tab=peserta')->with('success', $msg);
    }

    public function show(Training $training, TrainingParticipant $participant): RedirectResponse
    {
        if ($participant->training_id !== $training->id) {
            return redirect(route('admin.training.show', $training).'?tab=peserta')->with('error', 'Peserta tidak ditemukan untuk training ini.');
        }

        $participant->load(['employee.user', 'employee.department', 'employee.position']);

        return redirect(route('admin.training.show', $training).'?tab=peserta')->with([
            'participant_detail' => $participant,
            'participant_detail_modal' => true,
        ]);
    }

    public function destroy(Training $training, TrainingParticipant $participant): RedirectResponse
    {
        if ($participant->training_id !== $training->id) {
            return redirect(route('admin.training.show', $training).'?tab=peserta')->with('error', 'Peserta tidak ditemukan untuk training ini.');
        }

        if ($participant->pre_test_status !== 'not_started'
            || $participant->material_status !== 'locked'
            || $participant->post_test_status !== 'locked'
            || $participant->pre_test_score !== null
            || $participant->post_test_score !== null) {
            return redirect(route('admin.training.show', $training).'?tab=peserta')->with('error', 'Peserta sudah memiliki progress atau nilai. Biarkan sebagai histori training.');
        }

        $participant->forceDelete();

        return redirect(route('admin.training.show', $training).'?tab=peserta')->with('success', 'Peserta berhasil dihapus dari training.');
    }

    private function resolveEmployeeIds(Request $request): array
    {
        return match ($request->input('assignment_type')) {
            'all' => Employee::where('status', 'active')->pluck('id')->toArray(),
            'department' => Employee::where('status', 'active')
                ->where('department_id', $request->input('department_id'))
                ->pluck('id')
                ->toArray(),
            'position' => Employee::where('status', 'active')
                ->where('position_id', $request->input('position_id'))
                ->pluck('id')
                ->toArray(),
            'employees' => (array) $request->input('employee_ids', []),
            default => [],
        };
    }
}
