<?php

namespace Tests\Feature;

use App\Exceptions\IaNoDisponible;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Services\AsistenteIA;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

// Ninguna prueba llama a la API real: Http::fake() responde en lugar de Groq.
class AsistenteIATest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.groq.key' => 'clave-groq',
            'services.groq.model' => 'openai/gpt-oss-20b',
        ]);
    }

    private function respuesta(string $texto): array
    {
        return ['choices' => [['message' => ['role' => 'assistant', 'content' => $texto]]]];
    }

    // --- Día 1: la llamada a la API ---
    public function test_le_pregunta_a_groq_con_la_clave_y_el_modelo(): void
    {
        Http::fake(['api.groq.com/*' => Http::response($this->respuesta('Sí, queda bien.'))]);

        $texto = app(AsistenteIA::class)->preguntar([['role' => 'user', 'content' => 'Hola']]);

        $this->assertSame('Sí, queda bien.', $texto);
        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.groq.com/openai/v1/chat/completions'
            && $request->hasHeader('Authorization', 'Bearer clave-groq')
            && $request['model'] === 'openai/gpt-oss-20b'
            && $request['messages'][0]['content'] === 'Hola');
    }

    public function test_si_groq_devuelve_un_error_lanza_ia_no_disponible(): void
    {
        Http::fake(['api.groq.com/*' => Http::response(['error' => 'rate limit'], 429)]);

        $this->expectException(IaNoDisponible::class);

        app(AsistenteIA::class)->preguntar([['role' => 'user', 'content' => 'Hola']]);
    }

    public function test_si_groq_responde_vacio_lanza_ia_no_disponible(): void
    {
        Http::fake(['api.groq.com/*' => Http::response($this->respuesta('   '))]);

        $this->expectException(IaNoDisponible::class);

        app(AsistenteIA::class)->preguntar([['role' => 'user', 'content' => 'Hola']]);
    }

    public function test_sin_clave_no_llama_a_groq(): void
    {
        config(['services.groq.key' => null]);
        Http::fake();

        try {
            app(AsistenteIA::class)->preguntar([['role' => 'user', 'content' => 'Hola']]);
            $this->fail('Debió lanzar IaNoDisponible.');
        } catch (IaNoDisponible) {
            Http::assertNothingSent();
        }
    }

    // --- Día 2: reemplazos y conversación ---
    public function test_alternativas_devuelve_una_por_linea_y_sin_vinetas(): void
    {
        $recipe = Recipe::factory()->create(['title' => 'Charquicán']);
        $zapallo = Ingredient::factory()->for($recipe)->create(['name' => 'Zapallo', 'quantity' => '300 g']);
        Http::fake(['api.groq.com/*' => Http::response($this->respuesta(
            "- Sin Zapallo → zanahoria rallada (2 unidades)\n\n2. Sin Zapallo → 1 papa más"
        ))]);

        $alternativas = app(AsistenteIA::class)->alternativas($recipe, $zapallo);

        $this->assertSame([
            'Sin Zapallo → zanahoria rallada (2 unidades)',
            'Sin Zapallo → 1 papa más',
        ], $alternativas);
        Http::assertSent(fn (Request $request) => str_contains($request['messages'][1]['content'], 'Zapallo (300 g)')
            && str_contains($request['messages'][1]['content'], 'Charquicán'));
    }

    public function test_conversar_envia_las_reglas_la_receta_y_el_historial(): void
    {
        $recipe = Recipe::factory()->create(['title' => 'Charquicán']);
        Http::fake(['api.groq.com/*' => Http::response($this->respuesta('Unos 15 minutos.'))]);

        $texto = app(AsistenteIA::class)->conversar(
            [['role' => 'user', 'content' => '¿Cuánto se demora?']],
            $recipe,
            'Camila',
        );

        $this->assertSame('Unos 15 minutos.', $texto);
        Http::assertSent(function (Request $request) {
            [$sistema, $pregunta] = $request['messages'];

            return $sistema['role'] === 'system'
                && str_contains($sistema['content'], 'YouCook')
                && str_contains($sistema['content'], 'Camila')
                && str_contains($sistema['content'], 'Charquicán')
                && $pregunta['content'] === '¿Cuánto se demora?';
        });
    }
}
