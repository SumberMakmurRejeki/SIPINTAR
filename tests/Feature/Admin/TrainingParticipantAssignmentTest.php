<?php

namespace Tests\Feature\Admin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingParticipantAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_assign_all_active_employees(): void
    {
        $training = Training::factory()->create(['created_by' => $this->admin->id]);
        $this->createEmployee();
        $this->createEmployee();

        $response = $this->actingAs($this->admin)->post(route('admin.training.participants.store', $training), [
            'assignment_type' => 'all',
            'department_id' => '',
            'position_id' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('training_participants', 2);
    }

    public function test_admin_can_assign_by_department(): void
    {
        $training = Training::factory()->create(['created_by' => $this->admin->id]);
        $department = Department::factory()->create();
        $otherDepartment = Department::factory()->create();

        $this->createEmployee($department->id);
        $this->createEmployee($department->id);
        $this->createEmployee($otherDepartment->id);

        $response = $this->actingAs($this->admin)->post(route('admin.training.participants.store', $training), [
            'assignment_type' => 'department',
            'department_id' => $department->id,
            'position_id' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('training_participants', 2);
    }

    public function test_admin_can_assign_selected_employees(): void
    {
        $training = Training::factory()->create(['created_by' => $this->admin->id]);
        $employeeOne = $this->createEmployee();
        $employeeTwo = $this->createEmployee();
        $this->createEmployee();

        $response = $this->actingAs($this->admin)->post(route('admin.training.participants.store', $training), [
            'assignment_type' => 'employees',
            'department_id' => '',
            'position_id' => '',
            'employee_ids' => [$employeeOne->id, $employeeTwo->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('training_participants', 2);
    }

    private function createEmployee(?int $departmentId = null): Employee
    {
        $departmentId ??= Department::factory()->create()->id;
        $positionId = Position::factory()->create()->id;

        $user = User::factory()->create([
            'role' => 'employee',
            'status' => 'active',
        ]);

        return Employee::factory()->create([
            'user_id' => $user->id,
            'department_id' => $departmentId,
            'position_id' => $positionId,
        ]);
    }
}
