<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_perfil_muestra_el_progreso_del_reto(): void
    {
        $user = User::factory()->create(['name' => 'Camila Rojas']);
        $recipes = Recipe::factory()->count(4)->create();
        $cocinada = $recipes->first();
        $user->cookedRecipes()->attach($cocinada);

        $response = $this->actingAs($user)->get('/perfil');

        $response
            ->assertOk()
            ->assertSee('Camila Rojas')
            ->assertSee('1 de 4 recetas cocinadas')
            ->assertSee('25%')
            ->assertSee($cocinada->sticker_name)
            ->assertSee('Cocinera en entrenamiento');
    }

    public function test_se_pueden_actualizar_el_nombre_y_el_correo(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/perfil', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/perfil');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
    }

    public function test_el_correo_no_puede_ser_el_de_otra_persona(): void
    {
        $otra = User::factory()->create();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/perfil')
            ->patch('/perfil', [
                'name' => $user->name,
                'email' => $otra->email,
            ]);

        $response
            ->assertSessionHasErrors('email')
            ->assertRedirect('/perfil');
    }

    public function test_el_perfil_exige_iniciar_sesion(): void
    {
        $this->get('/perfil')->assertRedirect('/login');
    }
}