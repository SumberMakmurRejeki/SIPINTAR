<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => Department::inRandomOrder()->first() ?? Department::factory(),
            'position_id' => Position::inRandomOrder()->first() ?? Position::factory(),
            'employee_code' => fake()->unique()->numerify('EMP-#####'),
            'name' => fake()->name(),
            'status' => 'active',
        ];
    }
}
