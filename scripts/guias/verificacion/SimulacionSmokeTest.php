<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

// Solo para scripts/guias/simular_integracion.sh: abre todas las páginas con
// los datos del seeder y comprueba que ninguna falle en este corte.
class SimulacionSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_todas_las_paginas_abren(): void
    {
        $this->seed();
        $user = User::first();

        foreach (['/login', '/registro'] as $url) {
            $this->get($url)->assertOk();
        }

        $slug = Schema::hasTable('recipes') && \App\Models\Recipe::count() > 0
            ? \App\Models\Recipe::first()->slug
            : 'cualquiera';

        $sinFoto = Schema::hasTable('recipes') ? \App\Models\Recipe::whereNull('image')->value('slug') : null;

        $urls = ['/recetas', "/recetas/{$slug}", '/recetas?q=zapallo&categoria=Chilenas&tiempo=60&dificultad=Media',
            '/recetas?page=2', '/recetas?q=nada-de-nada', '/guardados', '/asistente', "/asistente?receta={$slug}", '/perfil'];

        if ($sinFoto) {
            $urls[] = "/recetas/{$sinFoto}";
        }

        foreach ($urls as $url) {
            $response = $this->actingAs($user)->get($url);
            $this->assertSame(200, $response->status(), "Falló {$url}");
        }

        // Una persona recién registrada (sin guardados ni cocinadas) también ve todo.
        $nueva = User::factory()->create();

        foreach (['/recetas', '/guardados', '/asistente', '/perfil'] as $url) {
            $response = $this->actingAs($nueva)->get($url);
            $this->assertSame(200, $response->status(), "Falló {$url} (cuenta nueva)");
        }
    }
}
