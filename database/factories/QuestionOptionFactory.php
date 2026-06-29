<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionOption>
 */
class QuestionOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'option_label' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'option_text' => fake()->sentence(),
            'is_correct' => false,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
