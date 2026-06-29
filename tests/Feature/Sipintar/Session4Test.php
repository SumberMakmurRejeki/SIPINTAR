<?php

namespace Tests\Feature\Sipintar;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Session4Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    // === Departemen ===

    public function test_admin_can_list_departments(): void
    {
        Department::factory()->count(3)->create();

        $this->actingAs($this->admin)
            ->get(route('admin.master.departemen.index'))
            ->assertOk();
    }

    public function test_admin_can_create_department(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.master.departemen.store'), [
            'name' => 'Engineering',
            'description' => 'Engineering department',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.master.departemen.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Engineering']);
    }

    public function test_admin_can_show_department(): void
    {
        $dept = Department::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.master.departemen.show', $dept))
            ->assertOk()
            ->assertSee($dept->name);
    }

    public function test_admin_can_edit_department(): void
    {
        $dept = Department::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.master.departemen.update', $dept), [
            'name' => 'Engineering Updated',
            'description' => 'Updated',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.master.departemen.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Engineering Updated']);
    }

    public function test_admin_can_toggle_department_status(): void
    {
        $dept = Department::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->patch(route('admin.master.departemen.toggle-status', $dept));

        $response->assertRedirect();
        $this->assertDatabaseHas('departments', ['id' => $dept->id, 'status' => 'inactive']);
    }

    public function test_admin_can_delete_department_without_employees(): void
    {
        $dept = Department::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.master.departemen.destroy', $dept));

        $response->assertRedirect(route('admin.master.departemen.index'));
        $this->assertSoftDeleted('departments', ['id' => $dept->id]);
    }

    public function test_admin_cannot_delete_department_with_employees(): void
    {
        $dept = Department::factory()->create();
        $position = Position::factory()->create();
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        Employee::factory()->create(['user_id' => $user->id, 'department_id' => $dept->id, 'position_id' => $position->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.master.departemen.destroy', $dept));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('departments', ['id' => $dept->id, 'deleted_at' => null]);
    }

    // === Jabatan ===

    public function test_admin_can_create_position(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.master.jabatan.store'), [
            'name' => 'Software Engineer',
            'description' => 'Dev role',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.master.jabatan.index'));
        $this->assertDatabaseHas('positions', ['name' => 'Software Engineer']);
    }

    public function test_admin_can_toggle_position_status(): void
    {
        $position = Position::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->patch(route('admin.master.jabatan.toggle-status', $position));

        $response->assertRedirect();
        $this->assertDatabaseHas('positions', ['id' => $position->id, 'status' => 'inactive']);
    }

    public function test_admin_cannot_delete_position_with_employees(): void
    {
        $dept = Department::factory()->create();
        $position = Position::factory()->create();
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        Employee::factory()->create(['user_id' => $user->id, 'department_id' => $dept->id, 'position_id' => $position->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.master.jabatan.destroy', $position));

        $response->assertSessionHas('error');
    }

    // === Admin User ===

    public function test_admin_can_create_admin_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.master.admin-user.store'), [
            'name' => 'New Admin',
            'username' => 'newadmin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.master.admin-user.index'));
        $this->assertDatabaseHas('users', ['name' => 'New Admin', 'role' => 'admin']);
    }

    public function test_admin_cannot_deactivate_self(): void
    {
        $response = $this->actingAs($this->admin)->patch(route('admin.master.admin-user.toggle-status', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'status' => 'active']);
    }

    public function test_admin_can_deactivate_non_last_admin(): void
    {
        // Create 2 other admins so there are 3 total (including current)
        $otherAdmin1 = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $otherAdmin2 = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->patch(route('admin.master.admin-user.toggle-status', $otherAdmin1));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $otherAdmin1->id, 'status' => 'inactive']);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.master.admin-user.destroy', $this->admin));

        $response->assertSessionHas('error');
    }

    public function test_admin_can_delete_non_last_admin(): void
    {
        $otherAdmin1 = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $otherAdmin2 = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->delete(route('admin.master.admin-user.destroy', $otherAdmin1));

        $response->assertRedirect(route('admin.master.admin-user.index'));
        $this->assertSoftDeleted('users', ['id' => $otherAdmin1->id]);
    }

    public function test_employee_cannot_access_master_data(): void
    {
        $dept = Department::factory()->create();
        $position = Position::factory()->create();
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        Employee::factory()->create(['user_id' => $user->id, 'department_id' => $dept->id, 'position_id' => $position->id]);

        $routes = [
            route('admin.master.departemen.index'),
            route('admin.master.jabatan.index'),
            route('admin.master.karyawan.index'),
            route('admin.master.admin-user.index'),
        ];

        foreach ($routes as $route) {
            $this->actingAs($user)->get($route)->assertForbidden();
        }
    }

    public function test_department_name_must_be_unique(): void
    {
        Department::factory()->create(['name' => 'HR']);

        $response = $this->actingAs($this->admin)->post(route('admin.master.departemen.store'), [
            'name' => 'HR',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_position_name_must_be_unique(): void
    {
        Position::factory()->create(['name' => 'Manager']);

        $response = $this->actingAs($this->admin)->post(route('admin.master.jabatan.store'), [
            'name' => 'Manager',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
