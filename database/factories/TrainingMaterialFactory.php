<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\TrainingMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingMaterial>
 */
class TrainingMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['file', 'link']);

        return [
            'training_id' => Training::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'type' => $type,
            'file_path' => $type === 'file' ? fake()->filePath() : null,
            'file_type' => $type === 'file' ? fake()->randomElement(['pdf', 'docx', 'xlsx', 'pptx']) : null,
            'url' => $type === 'link' ? fake()->url() : null,
            'is_downloadable' => fake()->boolean(),
            'is_required' => fake()->boolean(80),
            'sort_order' => fake()->numberBetween(0, 100),
            'status' => 'active',
        ];
    }
}
