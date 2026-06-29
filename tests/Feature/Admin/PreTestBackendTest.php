<?php

namespace Tests\Feature\Admin;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreTestBackendTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Training $training;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $this->training = Training::factory()->create(['created_by' => $this->admin->id]);
    }

    public function test_admin_can_create_and_update_pre_test_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.training.pre-test.store', $this->training), [
            'title' => 'Pre-Test Dasar',
            'instruction' => 'Kerjakan sebelum training.',
            'duration_minutes' => 30,
            'status' => 'active',
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('tests', [
            'training_id' => $this->training->id,
            'type' => 'pre_test',
            'title' => 'Pre-Test Dasar',
        ]);

        $test = Test::where('training_id', $this->training->id)->where('type', 'pre_test')->firstOrFail();

        $this->actingAs($this->admin)->put(route('admin.training.pre-test.update', [$this->training, $test]), [
            'title' => 'Pre-Test Updated',
            'instruction' => null,
            'duration_minutes' => 45,
            'status' => 'inactive',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('tests', [
            'id' => $test->id,
            'title' => 'Pre-Test Updated',
            'duration_minutes' => 45,
            'status' => 'inactive',
        ]);
    }

    public function test_multiple_choice_question_requires_a_correct_option(): void
    {
        $test = Test::factory()->create(['training_id' => $this->training->id, 'type' => 'pre_test']);

        $response = $this->actingAs($this->admin)->post(route('admin.training.pre-test.questions.store', [$this->training, $test]), [
            'question_text' => 'Pilih jawaban benar.',
            'question_type' => 'multiple_choice',
            'score' => 10,
            'sort_order' => 0,
            'status' => 'active',
            'options' => [
                ['option_label' => 'A', 'option_text' => 'Salah', 'is_correct' => false],
            ],
        ]);

        $response->assertSessionHasErrors('options');
    }

    public function test_admin_can_create_update_toggle_preview_and_delete_pre_test_question(): void
    {
        $test = Test::factory()->create(['training_id' => $this->training->id, 'type' => 'pre_test']);

        $this->actingAs($this->admin)->post(route('admin.training.pre-test.questions.store', [$this->training, $test]), [
            'question_text' => 'Apa kepanjangan K3?',
            'question_type' => 'multiple_choice',
            'score' => 10,
            'sort_order' => 1,
            'status' => 'active',
            'options' => [
                ['option_label' => 'A', 'option_text' => 'Keselamatan dan Kesehatan Kerja', 'is_correct' => true],
                ['option_label' => 'B', 'option_text' => 'Keamanan Kantor', 'is_correct' => false],
            ],
        ])->assertRedirect()->assertSessionHas('success');

        $question = Question::where('test_id', $test->id)->firstOrFail();
        $this->assertDatabaseCount('question_options', 2);

        $this->actingAs($this->admin)->put(route('admin.training.pre-test.questions.update', [$this->training, $test, $question]), [
            'question_text' => 'Jelaskan tujuan K3.',
            'question_type' => 'essay',
            'score' => 15,
            'sort_order' => 2,
            'status' => 'active',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_type' => 'essay',
            'score' => 15,
        ]);
        $this->assertDatabaseCount('question_options', 0);

        $this->actingAs($this->admin)->patch(route('admin.training.pre-test.questions.toggle-status', [$this->training, $test, $question]))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseHas('questions', ['id' => $question->id, 'status' => 'inactive']);

        $this->actingAs($this->admin)->get(route('admin.training.pre-test.preview', [$this->training, $test]))
            ->assertOk()
            ->assertViewIs('pages::admin.training.partials.pre-test-preview');

        $option = QuestionOption::factory()->create(['question_id' => $question->id]);

        $this->actingAs($this->admin)->delete(route('admin.training.pre-test.questions.destroy', [$this->training, $test, $question]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
        $this->assertDatabaseMissing('question_options', ['id' => $option->id]);
    }
}
