<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssistantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.groq.key' => 'clave-groq',
        ]);
    }

    private function iaResponde(string $texto): void
    {
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => $texto]]]])]);
    }

    public function test_la_pantalla_del_asistente_saluda_por_el_nombre(): void
    {
        $user = User::factory()->create(['name' => 'Camila Rojas']);

        $this->actingAs($user)->get('/asistente')
            ->assertOk()
            ->assertSee('¡Hola, Camila!');
    }

    public function test_llegar_desde_una_receta_la_deja_como_tema(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['slug' => 'charquican', 'title' => 'Charquicán']);

        $this->actingAs($user)->get('/asistente?receta=charquican')
            ->assertOk()
            ->assertSee('Hablando sobre')
            ->assertSessionHas('asistente.receta_id', $recipe->id);
    }

    public function test_enviar_un_mensaje_responde_y_guarda_la_conversacion(): void
    {
        $this->iaResponde('Sí, hasta 2 meses.');
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/asistente', ['mensaje' => '¿Puedo congelarlo?'])
            ->assertOk()
            ->assertExactJson(['respuesta' => 'Sí, hasta 2 meses.'])
            ->assertSessionHas('asistente.mensajes', [
                ['role' => 'user', 'content' => '¿Puedo congelarlo?'],
                ['role' => 'assistant', 'content' => 'Sí, hasta 2 meses.'],
            ]);
    }

    public function test_un_mensaje_vacio_no_se_envia_a_la_ia(): void
    {
        Http::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/asistente', ['mensaje' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('mensaje');

        Http::assertNothingSent();
    }

    public function test_si_la_ia_no_responde_devuelve_503_y_no_guarda_nada(): void
    {
        Http::fake(['*' => Http::response('Error', 500)]);
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/asistente', ['mensaje' => 'Hola'])
            ->assertStatus(503)
            ->assertSessionMissing('asistente.mensajes');
    }

    public function test_nueva_conversacion_borra_los_mensajes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['asistente' => ['receta_id' => null, 'mensajes' => [['role' => 'user', 'content' => 'Hola']]]])
            ->delete('/asistente')
            ->assertRedirect('/asistente')
            ->assertSessionMissing('asistente.mensajes');
    }

    public function test_no_lo_tengo_devuelve_alternativas_y_las_guarda_en_cache(): void
    {
        $this->iaResponde("Sin zapallo → zanahoria rallada\nSin zapallo → una papa más");
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->for($recipe)->create(['name' => 'Zapallo']);
        $url = "/recetas/{$recipe->slug}/ingredientes/{$ingredient->id}/alternativas";

        $this->actingAs($user)->postJson($url)
            ->assertOk()
            ->assertExactJson(['alternativas' => ['Sin zapallo → zanahoria rallada', 'Sin zapallo → una papa más']]);

        // La segunda vez responde la caché: no hay otra llamada a la API.
        $this->actingAs($user)->postJson($url)->assertOk();
        Http::assertSentCount(1);
    }

    public function test_alternativas_con_la_ia_caida_devuelve_503(): void
    {
        Http::fake(['*' => Http::response('Error', 500)]);
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->for($recipe)->create();

        $this->actingAs($user)
            ->postJson("/recetas/{$recipe->slug}/ingredientes/{$ingredient->id}/alternativas")
            ->assertStatus(503);
    }

    public function test_un_ingrediente_de_otra_receta_responde_404(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $ajeno = Ingredient::factory()->create();

        $this->actingAs($user)
            ->postJson("/recetas/{$recipe->slug}/ingredientes/{$ajeno->id}/alternativas")
            ->assertNotFound();
    }
}
