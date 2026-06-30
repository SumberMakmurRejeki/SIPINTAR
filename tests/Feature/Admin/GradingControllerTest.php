<?php

namespace Tests\Feature\Admin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradingControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_admin_can_grade_waiting_post_test_essay_attempt(): void
    {
        [$attempt, $essayAnswer, $participant] = $this->makeWaitingAttempt('post_test', 70);

        $response = $this->actingAs($this->admin)->post(route('admin.grading.grade', $attempt), [
            'scores' => [
                $essayAnswer->id => 50,
            ],
        ]);

        $response->assertRedirect(route('admin.grading.index'));
        $this->assertDatabaseHas('test_answers', [
            'id' => $essayAnswer->id,
            'manual_score' => 50.00,
            'graded_by' => $this->admin->id,
        ]);
        $this->assertDatabaseHas('test_attempts', [
            'id' => $attempt->id,
            'status' => 'graded',
            'multiple_choice_score' => 30.00,
            'essay_score' => 50.00,
            'final_score' => 80.00,
        ]);
        $this->assertDatabaseHas('training_participants', [
            'id' => $participant->id,
            'progress_status' => 'passed',
            'post_test_status' => 'completed',
            'grading_status' => 'graded',
            'final_score' => 80.00,
        ]);
    }

    public function test_admin_cannot_grade_essay_above_question_score(): void
    {
        [$attempt, $essayAnswer] = $this->makeWaitingAttempt('pre_test', 70);

        $response = $this->actingAs($this->admin)->from(route('admin.grading.show', $attempt))
            ->post(route('admin.grading.grade', $attempt), [
                'scores' => [
                    $essayAnswer->id => 51,
                ],
            ]);

        $response->assertRedirect(route('admin.grading.show', $attempt));
        $response->assertSessionHasErrors('scores.'.$essayAnswer->id);
        $this->assertDatabaseHas('test_attempts', [
            'id' => $attempt->id,
            'status' => 'waiting_grading',
        ]);
    }

    /**
     * @return array{0: TestAttempt, 1: TestAnswer, 2: TrainingParticipant}
     */
    private function makeWaitingAttempt(string $testType, int $passingGrade): array
    {
        $employeeUser = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $employeeUser->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);
        $training = Training::factory()->create(['status' => 'published']);
        $participant = TrainingParticipant::factory()->create([
            'training_id' => $training->id,
            'employee_id' => $employee->id,
            'progress_status' => 'waiting_grading',
            'pre_test_status' => $testType === 'pre_test' ? 'completed' : 'completed',
            'material_status' => 'completed',
            'post_test_status' => $testType === 'post_test' ? 'waiting_grading' : 'locked',
            'grading_status' => 'waiting',
        ]);
        $test = Test::factory()->create([
            'training_id' => $training->id,
            'type' => $testType,
            'status' => 'active',
            'passing_grade' => $passingGrade,
            'max_attempts' => 2,
        ]);
        $attempt = TestAttempt::factory()->create([
            'test_id' => $test->id,
            'training_participant_id' => $participant->id,
            'employee_id' => $employee->id,
            'status' => 'waiting_grading',
            'submitted_at' => now(),
            'multiple_choice_score' => 30,
        ]);

        $multipleChoiceQuestion = Question::factory()->create([
            'test_id' => $test->id,
            'question_type' => 'multiple_choice',
            'score' => 30,
            'sort_order' => 1,
        ]);
        $selectedOption = QuestionOption::factory()->create([
            'question_id' => $multipleChoiceQuestion->id,
            'is_correct' => true,
        ]);
        TestAnswer::factory()->create([
            'test_attempt_id' => $attempt->id,
            'question_id' => $multipleChoiceQuestion->id,
            'selected_option_id' => $selectedOption->id,
            'auto_score' => 30,
            'is_correct' => true,
        ]);

        $essayQuestion = Question::factory()->create([
            'test_id' => $test->id,
            'question_type' => 'essay',
            'score' => 50,
            'sort_order' => 2,
        ]);
        $essayAnswer = TestAnswer::factory()->create([
            'test_attempt_id' => $attempt->id,
            'question_id' => $essayQuestion->id,
            'essay_answer' => 'Jawaban essay peserta.',
        ]);

        return [$attempt, $essayAnswer, $participant];
    }
}
