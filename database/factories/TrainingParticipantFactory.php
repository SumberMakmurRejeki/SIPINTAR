<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingParticipant>
 */
class TrainingParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_id' => Training::factory(),
            'employee_id' => Employee::factory(),
            'progress_status' => 'not_started',
            'pre_test_status' => 'locked',
            'material_status' => 'locked',
            'post_test_status' => 'locked',
            'grading_status' => 'none',
        ];
    }
}
