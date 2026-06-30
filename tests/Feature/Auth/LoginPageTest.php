<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('SI-PINTAR');
        $response->assertSee('Masuk ke akun Anda');
        $response->assertSee('Masukkan username Anda');
        $response->assertSee('Masukkan password Anda');
        $response->assertSee('Ingat saya');
    }

    public function test_login_page_shows_the_password_reset_link_when_available(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Lupa password?');
    }
}
