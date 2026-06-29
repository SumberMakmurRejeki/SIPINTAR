<?php

namespace Tests\Feature\Sipintar;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Session1Test extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_employee_can_login_and_redirect_to_employee_dashboard(): void
    {
        $employee = User::factory()->create([
            'username' => 'karyawan1',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'username' => 'karyawan1',
            'password' => 'password',
        ]);

        $response->assertRedirect('/karyawan/dashboard');
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_unauthenticated_user_cannot_access_employee_dashboard(): void
    {
        $response = $this->get('/karyawan/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $employee = User::factory()->create([
            'username' => 'karyawan1',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee)->get('/admin/dashboard');
        $response->assertForbidden();
    }

    public function test_admin_cannot_access_employee_dashboard(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get('/karyawan/dashboard');
        $response->assertForbidden();
    }

    public function test_admin_dashboard_page_renders(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_employee_dashboard_page_renders(): void
    {
        $employee = User::factory()->create([
            'username' => 'karyawan1',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee)->get('/karyawan/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $inactive = User::factory()->create([
            'username' => 'inactive',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'username' => 'inactive',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
