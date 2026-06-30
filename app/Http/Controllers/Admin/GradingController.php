<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GradeEssayRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradingController extends Controller
{
    public function index(Request $request): View
    {
        $query = TestAttempt::with([
            'test.training',
            'employee.user',
            'employee.department',
            'employee.position',
            'trainingParticipant',
        ]);

        if ($request->filled('grading_status')) {
            $query->where('status', $request->grading_status === 'graded' ? 'graded' : 'waiting_grading');
        } else {
            $query->whereIn('status', ['waiting_grading', 'graded']);
        }

        if ($request->filled('training_id')) {
            $query->whereHas('test', fn ($testQuery) => $testQuery->where('training_id', $request->training_id));
        }

        if ($request->filled('test_type')) {
            $query->whereHas('test', fn ($testQuery) => $testQuery->where('type', $request->test_type));
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', fn ($employeeQuery) => $employeeQuery->where('department_id', $request->department_id));
        }

        if ($request->filled('position_id')) {
            $query->whereHas('employee', fn ($employeeQuery) => $employeeQuery->where('position_id', $request->position_id));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($searchQuery) use ($search): void {
                $searchQuery->whereHas('employee', fn ($employeeQuery) => $employeeQuery->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('test.training', fn ($trainingQuery) => $trainingQuery->where('title', 'like', '%'.$search.'%'));
            });
        }

        $attempts = $query
            ->orderByRaw("case when status = 'waiting_grading' then 0 else 1 end")
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

        $trainings = Training::whereHas('tests.attempts', fn ($attemptQuery) => $attemptQuery->where('status', 'waiting_grading'))
            ->orderBy('title')
            ->get();
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('status', 'active')->orderBy('name')->get();

        return view('pages::admin.grading.index', compact('attempts', 'trainings', 'departments', 'positions'));
    }

    public function show(TestAttempt $attempt): View
    {
        abort_unless(in_array($attempt->status, ['waiting_grading', 'graded']), 404);

        $attempt->load([
            'test.training',
            'employee.user',
            'employee.department',
            'employee.position',
            'answers' => function ($query) {
                $query->with([
                    'question.options' => fn ($optionQuery) => $optionQuery->orderBy('sort_order'),
                    'selectedOption',
                ])->whereHas('question');
            },
        ]);

        $attempt->setRelation('answers', $attempt->answers->sortBy([
            fn ($first, $second): int => $first->question->sort_order <=> $second->question->sort_order,
            fn ($first, $second): int => $first->id <=> $second->id,
        ])->values());

        $multipleChoiceScore = $attempt->answers
            ->filter(fn ($answer): bool => $answer->question->question_type === 'multiple_choice')
            ->sum(fn ($answer): float => (float) $answer->auto_score);

        return view('pages::admin.grading.show', compact('attempt', 'multipleChoiceScore'));
    }

    public function grade(GradeEssayRequest $request, TestAttempt $attempt): RedirectResponse
    {
        if ($attempt->status !== 'waiting_grading') {
            return redirect()->back()->with('error', 'Attempt ini sudah dinilai atau tidak menunggu penilaian.');
        }

        DB::transaction(function () use ($request, $attempt): void {
            $attempt->load(['test', 'trainingParticipant', 'answers.question']);

            $now = now();
            $scores = $request->validated('scores');

            foreach ($attempt->answers as $answer) {
                if ($answer->question->question_type !== 'essay') {
                    continue;
                }

                $answer->update([
                    'manual_score' => $scores[$answer->id],
                    'graded_by' => $request->user()->id,
                    'graded_at' => $now,
                ]);
            }

            $attempt->refresh()->load('answers.question');

            $multipleChoiceScore = $attempt->answers
                ->filter(fn ($answer): bool => $answer->question->question_type === 'multiple_choice')
                ->sum(fn ($answer): float => (float) $answer->auto_score);
            $essayScore = $attempt->answers
                ->filter(fn ($answer): bool => $answer->question->question_type === 'essay')
                ->sum(fn ($answer): float => (float) $answer->manual_score);
            $finalScore = $multipleChoiceScore + $essayScore;

            $attempt->update([
                'status' => 'graded',
                'multiple_choice_score' => $multipleChoiceScore,
                'essay_score' => $essayScore,
                'final_score' => $finalScore,
            ]);

            $this->updateParticipantAfterGrade($attempt->trainingParticipant, $attempt->test, $finalScore);
        });

        return redirect()->route('admin.grading.index')->with('success', 'Nilai essay berhasil disimpan.');
    }

    private function updateParticipantAfterGrade(TrainingParticipant $participant, Test $test, float $finalScore): void
    {
        $participant->loadMissing('training');

        if ($test->type === 'pre_test') {
            $updates = [
                'pre_test_score' => $finalScore,
                'pre_test_status' => 'completed',
                'progress_status' => 'in_progress',
            ];

            if ($participant->material_status === 'locked') {
                $updates['material_status'] = 'not_started';
            }

            $updates['grading_status'] = $this->hasWaitingAttempts($participant) ? 'waiting' : 'graded';

            $participant->update($updates);

            return;
        }

        $passingGrade = (float) ($test->passing_grade ?? $participant->training->passing_grade);
        $updates = [
            'post_test_score' => $finalScore,
            'final_score' => $finalScore,
            'post_test_status' => 'completed',
            'grading_status' => 'graded',
        ];

        if ($finalScore >= $passingGrade) {
            $updates['progress_status'] = 'passed';
            $updates['completed_at'] = now();
        } elseif ($this->hasRemainingAttempts($participant, $test)) {
            $updates['progress_status'] = 'retake';
            $updates['post_test_status'] = 'retake';
        } else {
            $updates['progress_status'] = 'failed';
            $updates['post_test_status'] = 'failed';
            $updates['completed_at'] = now();
        }

        $participant->update($updates);
    }

    private function hasWaitingAttempts(TrainingParticipant $participant): bool
    {
        return $participant->testAttempts()->where('status', 'waiting_grading')->exists();
    }

    private function hasRemainingAttempts(TrainingParticipant $participant, Test $test): bool
    {
        if (! $test->max_attempts) {
            return false;
        }

        $gradedAttempts = TestAttempt::where('test_id', $test->id)
            ->where('employee_id', $participant->employee_id)
            ->whereIn('status', ['graded', 'passed', 'failed'])
            ->count();

        return $gradedAttempts < $test->max_attempts;
    }
}
