<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringProgressController extends Controller
{
    public function index(Request $request): View
    {
        $query = TrainingParticipant::with([
            'training',
            'employee.user',
            'employee.department',
            'employee.position',
        ]);

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('department_id', $request->department_id));
        }

        if ($request->filled('position_id')) {
            $query->whereHas('employee', fn ($q) => $q->where('position_id', $request->position_id));
        }

        if ($request->filled('progress_status')) {
            $query->where('progress_status', $request->progress_status);
        }

        if ($request->filled('pre_test_status')) {
            $query->where('pre_test_status', $request->pre_test_status);
        }

        if ($request->filled('material_status')) {
            $query->where('material_status', $request->material_status);
        }

        if ($request->filled('post_test_status')) {
            $query->where('post_test_status', $request->post_test_status);
        }

        if ($request->filled('grading_status')) {
            $query->where('grading_status', $request->grading_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search): void {
                $q->whereHas('employee', fn ($eq) => $eq->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('training', fn ($tq) => $tq->where('title', 'like', '%'.$search.'%'));
            });
        }

        $participants = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $trainings = Training::orderBy('title')->get();
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('status', 'active')->orderBy('name')->get();

        return view('pages::admin.monitoring-progress.index', compact('participants', 'trainings', 'departments', 'positions'));
    }

    public function show(TrainingParticipant $participant): View
    {
        $participant->load([
            'training',
            'employee.user',
            'employee.department',
            'employee.position',
            'testAttempts.test',
            'testAttempts.answers.question',
            'testAttempts.answers.selectedOption',
            'materialAccessLogs.trainingMaterial',
        ]);

        $preTestAttempts = $participant->testAttempts
            ->filter(fn ($a) => $a->test?->type === 'pre_test')
            ->sortBy('attempt_number')
            ->values();

        $postTestAttempts = $participant->testAttempts
            ->filter(fn ($a) => $a->test?->type === 'post_test')
            ->sortBy('attempt_number')
            ->values();

        return view('pages::admin.monitoring-progress.show', compact(
            'participant',
            'preTestAttempts',
            'postTestAttempts',
        ));
    }
}
