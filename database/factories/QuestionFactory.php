<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
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
            'question_text' => fake()->sentence(),
            'question_type' => fake()->randomElement(['multiple_choice', 'essay']),
            'score' => 10,
            'sort_order' => fake()->numberBetween(0, 100),
            'status' => 'active',
        ];
    }
}
