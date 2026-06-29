<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'employee',
        ]);

        $this
            ->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this
            ->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk();
    }
}
