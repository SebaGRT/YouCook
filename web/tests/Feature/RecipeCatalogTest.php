<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_catalogo_exige_iniciar_sesion(): void
    {
        $this->get('/recetas')->assertRedirect('/login');
    }

    public function test_el_catalogo_muestra_ocho_recetas_por_pagina(): void
    {
        Recipe::factory()->count(9)->create();
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas')
            ->assertOk()
            ->assertSee('8 de 9 recetas')
            ->assertViewHas('recipes', fn ($recipes) => $recipes->count() === 8);

        $this->actingAs($user)->get('/recetas?page=2')
            ->assertViewHas('recipes', fn ($recipes) => $recipes->count() === 1);
    }

    public function test_busca_por_titulo_y_por_ingrediente(): void
    {
        $charquican = Recipe::factory()->create(['title' => 'Charquicán']);
        Ingredient::factory()->for($charquican)->create(['name' => 'Zapallo camote']);
        Recipe::factory()->create(['title' => 'Crema de zapallo']);
        Recipe::factory()->create(['title' => 'Arroz con huevo']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas?q=zapallo')
            ->assertOk()
            ->assertSee('Charquicán')
            ->assertSee('Crema de zapallo')
            ->assertDontSee('Arroz con huevo');
    }

    public function test_filtra_por_categoria_tiempo_y_dificultad(): void
    {
        Recipe::factory()->create(['title' => 'Rápida y fácil', 'category' => 'Rápidas', 'minutes' => 10, 'difficulty' => 'Fácil']);
        Recipe::factory()->create(['title' => 'Rápida pero lenta', 'category' => 'Rápidas', 'minutes' => 50, 'difficulty' => 'Fácil']);
        Recipe::factory()->create(['title' => 'Dulce difícil', 'category' => 'Dulces', 'minutes' => 10, 'difficulty' => 'Media']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas?categoria=Rápidas&tiempo=15&dificultad=Fácil')
            ->assertOk()
            ->assertSee('Rápida y fácil')
            ->assertDontSee('Rápida pero lenta')
            ->assertDontSee('Dulce difícil');
    }

    public function test_sin_resultados_muestra_el_estado_vacio(): void
    {
        Recipe::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas?q=inexistente')
            ->assertOk()
            ->assertSee('No encontramos recetas con esos filtros');
    }

    public function test_el_detalle_muestra_ingredientes_pasos_y_alergenos(): void
    {
        $recipe = Recipe::factory()->create([
            'slug' => 'charquican',
            'steps' => ['Pela las papas.', 'Sirve caliente.'],
            'allergens' => ['Huevo'],
            'cost' => 3500,
        ]);
        Ingredient::factory()->for($recipe)->create(['name' => 'Papas', 'quantity' => '4 medianas']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas/charquican')
            ->assertOk()
            ->assertSee($recipe->title)
            ->assertSee('Papas')
            ->assertSee('4 medianas')
            ->assertSee('Pela las papas.')
            ->assertSee('Huevo')
            ->assertSee('$3.500');
    }

    public function test_una_receta_que_no_existe_responde_404(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/recetas/no-existe')->assertNotFound();
    }
}
