<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilePasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => 'password',
        ]);

        $this->employee = User::factory()->create([
            'role' => 'employee',
            'status' => 'active',
            'password' => 'password',
        ]);

        Employee::factory()->create([
            'user_id' => $this->employee->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);
    }

    // --- Admin Profile Tests ---

    public function test_admin_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.profil-password'));

        $response->assertOk();
        $response->assertSee('Profil & Password');
        $response->assertSee($this->admin->name);
        $response->assertSee($this->admin->username);
    }

    public function test_admin_can_update_profile_name(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.profile.update'), [
            'name' => 'Nama Baru Admin',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Nama Baru Admin',
        ]);
    }

    public function test_admin_can_update_profile_email(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.profile.update'), [
            'name' => $this->admin->name,
            'email' => 'newadmin@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'email' => 'newadmin@example.com',
        ]);
    }

    public function test_admin_profile_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.profile.update'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_change_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.password.update'), [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', $this->admin->fresh()->password));
    }

    public function test_admin_password_change_rejects_wrong_current_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_password_change_requires_minimum_length(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.password.update'), [
            'current_password' => 'password',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_password_change_requires_confirmation(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profil-password.password.update'), [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // --- Employee Profile Tests ---

    public function test_employee_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->employee)->get(route('karyawan.profil-password'));

        $response->assertOk();
        $response->assertSee('Profil & Password');
        $response->assertSee($this->employee->name);
        $response->assertSee($this->employee->employee->employee_code);
    }

    public function test_employee_can_update_profile_name(): void
    {
        $response = $this->actingAs($this->employee)->put(route('karyawan.profil-password.profile.update'), [
            'name' => 'Nama Baru Karyawan',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->employee->id,
            'name' => 'Nama Baru Karyawan',
        ]);
    }

    public function test_employee_can_change_password(): void
    {
        $response = $this->actingAs($this->employee)->put(route('karyawan.profil-password.password.update'), [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', $this->employee->fresh()->password));
    }

    public function test_employee_password_change_rejects_wrong_current_password(): void
    {
        $response = $this->actingAs($this->employee)->put(route('karyawan.profil-password.password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    // --- Access Control Tests ---

    public function test_employee_cannot_access_admin_profile(): void
    {
        $response = $this->actingAs($this->employee)->get(route('admin.profil-password'));

        $response->assertForbidden();
    }

    public function test_admin_cannot_access_employee_profile(): void
    {
        $response = $this->actingAs($this->admin)->get(route('karyawan.profil-password'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('admin.profil-password'));

        $response->assertRedirect('/login');
    }
}
