<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\TrainingParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestAttempt>
 */
class TestAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_id' => Test::factory(),
            'training_participant_id' => TrainingParticipant::factory(),
            'employee_id' => fn (array $attrs) => TrainingParticipant::find($attrs['training_participant_id'])?->employee_id ?? Employee::factory(),
            'attempt_number' => 1,
            'status' => 'in_progress',
        ];
    }
}
