<?php

namespace Tests\Feature\Employee;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestAttemptTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;

    private Employee $employeeModel;

    private Training $training;

    private TrainingParticipant $participant;

    private Test $preTest;

    private Test $postTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $this->employeeModel = Employee::factory()->create([
            'user_id' => $this->employee->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);
        $this->training = Training::factory()->create(['status' => 'published']);
        $this->participant = TrainingParticipant::factory()->create([
            'training_id' => $this->training->id,
            'employee_id' => $this->employeeModel->id,
            'progress_status' => 'in_progress',
            'pre_test_status' => 'not_started',
            'material_status' => 'locked',
            'post_test_status' => 'locked',
        ]);

        $this->preTest = Test::factory()->create([
            'training_id' => $this->training->id,
            'type' => 'pre_test',
            'status' => 'active',
        ]);

        $this->postTest = Test::factory()->create([
            'training_id' => $this->training->id,
            'type' => 'post_test',
            'status' => 'active',
            'passing_grade' => 70.00,
        ]);
    }

    public function test_employee_can_view_test_start_page(): void
    {
        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.start', [$this->training, $this->preTest]))
            ->assertOk()
            ->assertViewIs('pages.employee.tests.start');
    }

    public function test_employee_cannot_access_test_from_other_training(): void
    {
        $otherTraining = Training::factory()->create(['status' => 'published']);
        $otherTest = Test::factory()->create(['training_id' => $otherTraining->id, 'status' => 'active']);

        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.start', [$otherTraining, $otherTest]))
            ->assertStatus(403);
    }

    public function test_employee_can_start_pre_test(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('karyawan.training-saya.tests.begin', [$this->training, $this->preTest]));

        $response->assertRedirect();
        $this->assertDatabaseHas('test_attempts', [
            'test_id' => $this->preTest->id,
            'employee_id' => $this->employeeModel->id,
            'status' => 'in_progress',
        ]);
        $this->assertDatabaseHas('training_participants', [
            'id' => $this->participant->id,
            'pre_test_status' => 'in_progress',
        ]);
    }

    public function test_employee_can_view_test_questions(): void
    {
        $question = Question::factory()->create(['test_id' => $this->preTest->id, 'question_type' => 'multiple_choice', 'status' => 'active']);
        QuestionOption::factory()->create(['question_id' => $question->id]);

        $attempt = TestAttempt::factory()->create([
            'test_id' => $this->preTest->id,
            'employee_id' => $this->employeeModel->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.show', [$this->training, $this->preTest, $attempt]))
            ->assertOk()
            ->assertViewIs('pages.employee.tests.show');
    }

    public function test_employee_cannot_access_others_attempt(): void
    {
        $otherUser = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $otherEmployee = Employee::factory()->create([
            'user_id' => $otherUser->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);
        $attempt = TestAttempt::factory()->create([
            'test_id' => $this->preTest->id,
            'employee_id' => $otherEmployee->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.show', [$this->training, $this->preTest, $attempt]))
            ->assertStatus(403);
    }

    public function test_employee_can_submit_pre_test_with_multiple_choice(): void
    {
        $question = Question::factory()->create([
            'test_id' => $this->preTest->id,
            'question_type' => 'multiple_choice',
            'score' => 10,
            'status' => 'active',
        ]);

        $correctOption = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);

        QuestionOption::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $attempt = TestAttempt::factory()->create([
            'test_id' => $this->preTest->id,
            'employee_id' => $this->employeeModel->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->employee)
            ->post(route('karyawan.training-saya.tests.submit', [$this->training, $this->preTest, $attempt]), [
                'answers' => [
                    $question->id => $correctOption->id,
                ],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('test_answers', [
            'test_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_option_id' => $correctOption->id,
            'is_correct' => true,
            'auto_score' => 10.00,
        ]);
        $this->assertDatabaseHas('test_attempts', [
            'id' => $attempt->id,
            'status' => 'graded',
            'multiple_choice_score' => 10.00,
        ]);
        $this->assertDatabaseHas('training_participants', [
            'id' => $this->participant->id,
            'pre_test_status' => 'completed',
            'material_status' => 'not_started',
        ]);
    }

    public function test_employee_can_submit_post_test_and_pass(): void
    {
        $this->participant->update([
            'pre_test_status' => 'completed',
            'material_status' => 'accessed',
            'post_test_status' => 'not_started',
        ]);

        $question = Question::factory()->create([
            'test_id' => $this->postTest->id,
            'question_type' => 'multiple_choice',
            'score' => 100,
            'status' => 'active',
        ]);

        $correctOption = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);

        $attempt = TestAttempt::factory()->create([
            'test_id' => $this->postTest->id,
            'employee_id' => $this->employeeModel->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->employee)
            ->post(route('karyawan.training-saya.tests.submit', [$this->training, $this->postTest, $attempt]), [
                'answers' => [
                    $question->id => $correctOption->id,
                ],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('training_participants', [
            'id' => $this->participant->id,
            'progress_status' => 'passed',
            'post_test_status' => 'completed',
            'final_score' => 100.00,
        ]);
    }

    public function test_employee_fails_post_test_when_below_passing_grade(): void
    {
        $this->participant->update([
            'pre_test_status' => 'completed',
            'material_status' => 'accessed',
            'post_test_status' => 'not_started',
        ]);

        // Set max_attempts to 1 so the first failure means no more attempts
        $this->postTest->update(['max_attempts' => 1]);

        $question = Question::factory()->create([
            'test_id' => $this->postTest->id,
            'question_type' => 'multiple_choice',
            'score' => 50,
            'status' => 'active',
        ]);

        $wrongOption = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $attempt = TestAttempt::factory()->create([
            'test_id' => $this->postTest->id,
            'employee_id' => $this->employeeModel->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($this->employee)
            ->post(route('karyawan.training-saya.tests.submit', [$this->training, $this->postTest, $attempt]), [
                'answers' => [
                    $question->id => $wrongOption->id,
                ],
            ]);

        $this->assertDatabaseHas('training_participants', [
            'id' => $this->participant->id,
            'progress_status' => 'failed',
            'post_test_status' => 'failed',
        ]);
    }

    public function test_post_test_locked_until_material_accessed(): void
    {
        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.start', [$this->training, $this->postTest]))
            ->assertStatus(403);
    }

    public function test_pre_test_cannot_be_restarted_after_completion(): void
    {
        $this->participant->update(['pre_test_status' => 'completed']);

        TestAttempt::factory()->create([
            'test_id' => $this->preTest->id,
            'employee_id' => $this->employeeModel->id,
            'training_participant_id' => $this->participant->id,
            'status' => 'graded',
        ]);

        $this->actingAs($this->employee)
            ->get(route('karyawan.training-saya.tests.start', [$this->training, $this->preTest]))
            ->assertStatus(400);
    }
}
