<?php

namespace Database\Factories;

use App\Models\Test;
use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Test>
 */
class TestFactory extends Factory
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
            'type' => fake()->randomElement(['pre_test', 'post_test']),
            'title' => fake()->sentence(3),
            'instruction' => fake()->optional()->paragraph(),
            'duration_minutes' => fake()->numberBetween(15, 120),
            'passing_grade' => 70.00,
            'max_attempts' => 2,
            'status' => 'active',
        ];
    }
}
