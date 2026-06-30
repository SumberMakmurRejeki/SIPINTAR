<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MaterialAccessLog;
use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrainingController extends Controller
{
    public function index(Request $request): View
    {
        $employee = $request->user()->employee;

        $query = Training::where('status', 'published')
            ->whereHas('trainingParticipants', fn ($q) => $q->where('employee_id', $employee->id))
            ->with(['trainingParticipants' => fn ($q) => $q->where('employee_id', $employee->id)])
            ->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->whereHas('trainingParticipants', function ($q) use ($request) {
                $q->where('employee_id', auth()->user()->employee->id)
                    ->where('progress_status', $request->status);
            });
        }

        $trainings = $query->paginate(10)->withQueryString();

        return view('pages.employee.training.index', compact('trainings'));
    }

    public function show(Training $training): View
    {
        $employee = auth()->user()->employee;

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        // Update progress on first view
        if ($participant->progress_status === 'not_started') {
            $participant->update([
                'progress_status' => 'in_progress',
                'started_at' => $participant->started_at ?? now(),
            ]);
        }

        $training->load([
            'materials' => fn ($q) => $q->where('status', 'active')->orderBy('sort_order'),
            'preTest',
            'postTest',
        ]);

        // Check which materials employee has accessed
        $accessedMaterialIds = MaterialAccessLog::where('employee_id', $employee->id)
            ->where('training_participant_id', $participant->id)
            ->pluck('training_material_id')
            ->toArray();

        return view('pages.employee.training.show', compact('training', 'participant', 'accessedMaterialIds'));
    }

    public function accessMaterial(Training $training, TrainingMaterial $material): RedirectResponse
    {
        $employee = auth()->user()->employee;

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        // Material locked if pre-test not completed
        abort_if($participant->pre_test_status !== 'completed', 403, 'Pre-test belum selesai.');

        abort_unless($material->training_id === $training->id && $material->status === 'active', 404);

        // Upsert access log - simple: create if not exists, update accessed_at if exists
        $existingLog = MaterialAccessLog::where('training_material_id', $material->id)
            ->where('employee_id', $employee->id)
            ->where('training_participant_id', $participant->id)
            ->first();

        if ($existingLog) {
            $existingLog->update(['accessed_at' => now()]);
        } else {
            MaterialAccessLog::create([
                'training_material_id' => $material->id,
                'employee_id' => $employee->id,
                'training_participant_id' => $participant->id,
                'accessed_at' => now(),
            ]);
        }

        // Update material_status and unlock post-test if all required materials accessed
        $totalRequired = TrainingMaterial::where('training_id', $training->id)
            ->where('status', 'active')
            ->where('is_required', true)
            ->count();

        $accessedRequired = MaterialAccessLog::where('employee_id', $employee->id)
            ->where('training_participant_id', $participant->id)
            ->whereHas('trainingMaterial', function ($q) use ($training) {
                $q->where('training_id', $training->id)
                    ->where('status', 'active')
                    ->where('is_required', true);
            })
            ->count();

        $updates = ['material_status' => 'accessed'];

        if ($totalRequired > 0 && $accessedRequired >= $totalRequired) {
            $updates['material_status'] = 'completed';
        }

        // Unlock post-test when material is accessed
        if ($participant->post_test_status === 'locked') {
            $updates['post_test_status'] = 'not_started';
        }

        $participant->update($updates);

        // Redirect to material (link or file preview)
        if ($material->type === 'link') {
            return redirect()->away($material->url);
        }

        return redirect()->route('karyawan.training-saya.show', $training);
    }

    public function downloadMaterial(Training $training, TrainingMaterial $material): StreamedResponse|RedirectResponse
    {
        $employee = auth()->user()->employee;

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        abort_unless($material->is_downloadable, 403);

        abort_unless($material->training_id === $training->id && $material->status === 'active', 404);

        if ($material->type === 'link') {
            return redirect()->away($material->url);
        }

        if (! $material->file_path || ! Storage::disk('public')->exists($material->file_path)) {
            return redirect()->route('karyawan.training-saya.show', $training)->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
