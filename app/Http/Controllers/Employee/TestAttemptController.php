<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestAttemptController extends Controller
{
    public function start(Training $training, Test $test): View
    {
        $employee = auth()->user()->employee;

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        $this->authorizeAccess($test, $participant);

        $hasInProgress = TestAttempt::where('test_id', $test->id)
            ->where('employee_id', $employee->id)
            ->where('status', 'in_progress')
            ->exists();

        return view('pages.employee.tests.start', compact('training', 'test', 'participant', 'hasInProgress'));
    }

    public function begin(Training $training, Test $test): RedirectResponse
    {
        $employee = auth()->user()->employee;

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        $this->authorizeAccess($test, $participant);

        $attempt = TestAttempt::where('test_id', $test->id)
            ->where('employee_id', $employee->id)
            ->where('status', 'in_progress')
            ->first();

        if (! $attempt) {
            $lastNumber = TestAttempt::where('test_id', $test->id)
                ->where('employee_id', $employee->id)
                ->max('attempt_number') ?? 0;

            $attempt = TestAttempt::create([
                'test_id' => $test->id,
                'training_participant_id' => $participant->id,
                'employee_id' => $employee->id,
                'attempt_number' => $lastNumber + 1,
                'status' => 'in_progress',
                'started_at' => now(),
            ]);

            if ($test->type === 'pre_test') {
                $participant->update(['pre_test_status' => 'in_progress']);
            } else {
                $participant->update(['post_test_status' => 'in_progress']);
            }
        }

        return redirect()->route('karyawan.training-saya.tests.show', [$training, $test, $attempt]);
    }

    public function show(Training $training, Test $test, TestAttempt $attempt): View
    {
        $employee = auth()->user()->employee;

        abort_unless($attempt->employee_id === $employee->id, 403);
        abort_unless($attempt->test_id === $test->id, 403);
        abort_unless($attempt->status === 'in_progress', 400, 'Attempt sudah diselesaikan.');

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        $questions = $test->questions()
            ->where('status', 'active')
            ->with(['options' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $answers = TestAnswer::where('test_attempt_id', $attempt->id)
            ->pluck('selected_option_id', 'question_id')
            ->toArray();

        $essayAnswers = TestAnswer::where('test_attempt_id', $attempt->id)
            ->pluck('essay_answer', 'question_id')
            ->toArray();

        return view('pages.employee.tests.show', compact('training', 'test', 'attempt', 'questions', 'answers', 'essayAnswers'));
    }

    public function submit(Training $training, Test $test, TestAttempt $attempt, Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;

        abort_unless($attempt->employee_id === $employee->id, 403);
        abort_unless($attempt->test_id === $test->id, 403);
        abort_unless($attempt->status === 'in_progress', 400, 'Attempt sudah diselesaikan.');

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('employee_id', $employee->id)
            ->first();

        abort_unless($participant, 403);

        $questions = $test->questions()
            ->where('status', 'active')
            ->with('options')
            ->get();

        $hasEssay = $questions->contains('question_type', 'essay');

        DB::beginTransaction();

        try {
            $totalAutoScore = 0;

            foreach ($questions as $question) {
                if ($question->question_type === 'multiple_choice') {
                    $selectedOptionId = $request->input("answers.{$question->id}");

                    abort_unless($selectedOptionId, 400, 'Semua soal wajib dijawab.');

                    $isValidOption = $question->options->contains('id', $selectedOptionId);
                    abort_unless($isValidOption, 400, 'Opsi jawaban tidak valid.');

                    $correctOption = $question->options->firstWhere('is_correct', true);
                    $isCorrect = $correctOption && $correctOption->id == $selectedOptionId;
                    $score = $isCorrect ? $question->score : 0;

                    $totalAutoScore += $score;

                    TestAnswer::create([
                        'test_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $selectedOptionId,
                        'is_correct' => $isCorrect,
                        'auto_score' => $score,
                    ]);
                } else {
                    $essayAnswer = $request->input("answers.{$question->id}");

                    abort_unless(trim((string) $essayAnswer) !== '', 400, 'Semua soal wajib dijawab.');

                    TestAnswer::create([
                        'test_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'essay_answer' => $essayAnswer,
                    ]);
                }
            }

            $status = $hasEssay ? 'waiting_grading' : 'graded';
            $finalScore = $hasEssay ? null : $totalAutoScore;

            $attempt->update([
                'status' => $status,
                'submitted_at' => now(),
                'multiple_choice_score' => $totalAutoScore,
                'final_score' => $finalScore,
            ]);

            $this->updateParticipantStatus($participant, $test, $totalAutoScore, $hasEssay);

            DB::commit();

            return redirect()->route('karyawan.training-saya.show', $training)
                ->with('success', $hasEssay
                    ? 'Test berhasil dikirim. Menunggu penilaian essay.'
                    : 'Test berhasil dikirim. Nilai: '.$totalAutoScore);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function authorizeAccess(Test $test, TrainingParticipant $participant): void
    {
        abort_unless($test->training_id === $participant->training_id, 404);
        abort_unless($test->status === 'active', 400, 'Test tidak aktif.');

        if ($test->type === 'pre_test') {
            abort_if($participant->pre_test_status === 'completed', 400, 'Pre-test sudah selesai.');
        } else {
            abort_if($participant->post_test_status === 'locked', 403, 'Post-test masih terkunci.');
            abort_if(in_array($participant->post_test_status, ['completed', 'failed']), 400, 'Post-test sudah selesai.');

            $attemptCount = TestAttempt::where('test_id', $test->id)
                ->where('employee_id', $participant->employee_id)
                ->whereIn('status', ['submitted', 'graded', 'passed', 'failed'])
                ->count();

            if ($test->max_attempts && $attemptCount >= $test->max_attempts) {
                abort(400, 'Batas percobaan habis.');
            }
        }
    }

    private function updateParticipantStatus(TrainingParticipant $participant, Test $test, float $autoScore, bool $hasEssay): void
    {
        if ($test->type === 'pre_test') {
            $updates = [
                'pre_test_status' => 'completed',
                'pre_test_score' => $autoScore,
                'material_status' => 'not_started',
            ];

            if (! $hasEssay) {
                $updates['progress_status'] = 'in_progress';
            }

            $participant->update($updates);

        } else {
            $participant->update([
                'post_test_score' => $autoScore,
            ]);

            if ($hasEssay) {
                $participant->update([
                    'post_test_status' => 'waiting_grading',
                    'grading_status' => 'waiting',
                    'progress_status' => 'waiting_grading',
                ]);

            } else {
                $passingGrade = $test->passing_grade ?? $participant->training->passing_grade;

                if ($autoScore >= $passingGrade) {
                    $participant->update([
                        'post_test_status' => 'completed',
                        'grading_status' => 'graded',
                        'progress_status' => 'passed',
                        'final_score' => $autoScore,
                        'completed_at' => now(),
                    ]);
                } else {
                    $completedAttempts = TestAttempt::where('test_id', $test->id)
                        ->where('employee_id', $participant->employee_id)
                        ->whereIn('status', ['submitted', 'graded', 'passed', 'failed'])
                        ->count();

                    $remainingAttempts = $test->max_attempts
                        ? $test->max_attempts - $completedAttempts
                        : 0;

                    if ($remainingAttempts > 0) {
                        $participant->update([
                            'post_test_status' => 'remedial',
                            'progress_status' => 'remedial',
                        ]);
                    } else {
                        $participant->update([
                            'post_test_status' => 'failed',
                            'progress_status' => 'failed',
                            'final_score' => $autoScore,
                            'completed_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
