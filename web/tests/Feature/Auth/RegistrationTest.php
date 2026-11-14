<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/registro');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('recetas.index', absolute: false));
    }

    public function test_the_password_needs_at_least_8_characters(): void
    {
        $this->from('/registro')->post('/registro', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_the_email_can_not_be_used_twice(): void
    {
        $this->post('/registro', [
            'name' => 'Otra Persona',
            'email' => 'camila@uni.cl',
            'password' => 'password',
        ]);
        auth()->logout();

        $this->from('/registro')->post('/registro', [
            'name' => 'Camila Rojas',
            'email' => 'camila@uni.cl',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }
}
