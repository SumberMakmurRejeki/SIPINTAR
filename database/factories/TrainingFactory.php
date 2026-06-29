<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'start_date' => fake()->optional()->dateTimeBetween('+1 week', '+1 month'),
            'end_date' => fake()->optional()->dateTimeBetween('+1 month', '+3 months'),
            'status' => fake()->randomElement(['draft', 'published', 'closed']),
            'created_by' => User::where('role', 'admin')->inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
