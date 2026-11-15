<?php

namespace App\Services;

use App\Exceptions\IaNoDisponible;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsistenteIA
{
    // --- Reglas del asistente ---
    private const REGLAS = <<<'TXT'
        Eres el asistente de cocina de YouCook, una app chilena de recetas simples para estudiantes.
        Responde siempre en español de Chile, en máximo 3 frases cortas y al grano, en texto plano (sin Markdown ni listas).
        Solo hablas de cocina: recetas, ingredientes, reemplazos, técnicas, conservación y seguridad de los alimentos.
        Si te piden otra cosa, responde que solo puedes ayudar con temas de cocina.
        Si un alimento o un reemplazo puede afectar a personas con alergias o intolerancias, adviértelo.
        Si no sabes algo, dilo: no inventes datos.
        TXT;

    // --- Llamada a la API de Groq ---
    /**
     * Le manda los mensajes a Groq y devuelve el texto de la respuesta.
     *
     * @param  array<int, array{role: string, content: string}>  $mensajes
     */
    public function preguntar(array $mensajes): string
    {
        // Sin clave, ni lo intentamos.
        if (blank(config('services.groq.key'))) {
            Log::warning('Falta GROQ_API_KEY en el .env.');

            throw new IaNoDisponible('Falta la clave de Groq.');
        }

        try {
            $respuesta = Http::withToken(config('services.groq.key'))
                ->timeout(20)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config('services.groq.model'),
                    'messages' => $mensajes,
                    'temperature' => 0.4,
                ]);
        } catch (ConnectionException $e) {
            // Sin internet, o Groq tardó más de 20 segundos.
            Log::error('No se pudo conectar con Groq', ['error' => $e->getMessage()]);

            throw new IaNoDisponible('Groq no respondió.');
        }

        if ($respuesta->failed()) {
            Log::error('Groq devolvió un error', [
                'status' => $respuesta->status(),
                'body' => $respuesta->json(),
            ]);

            throw new IaNoDisponible('Groq devolvió un error.');
        }

        $texto = $respuesta->json('choices.0.message.content');

        if (blank($texto)) {
            Log::warning('Groq respondió sin texto', ['body' => $respuesta->json()]);

            throw new IaNoDisponible('Groq respondió vacío.');
        }

        return trim($texto);
    }

    // --- Conversación del chat ---
    /**
     * @param  array<int, array{role: string, content: string}>  $historial
     */
    public function conversar(array $historial, ?Recipe $recipe, string $nombre): string
    {
        $contexto = self::REGLAS."\nLa persona se llama {$nombre}.";

        if ($recipe) {
            $contexto .= "\nEstá viendo esta receta:\n".$this->describir($recipe);
        }

        return $this->preguntar([
            ['role' => 'system', 'content' => $contexto],
            ...$historial,
        ]);
    }

    // --- Reemplazos de un ingrediente ---
    /**
     * @return array<int, string> Por ejemplo: ["Sin zapallo → zanahoria rallada (2 unidades)"]
     */
    public function alternativas(Recipe $recipe, Ingredient $ingredient): array
    {
        $pregunta = <<<TXT
            Para esta receta me falta un ingrediente: {$ingredient->name} ({$ingredient->quantity}).
            Dame 2 reemplazos fáciles de conseguir en Chile.
            Responde SOLO con 2 líneas, una por reemplazo, con este formato exacto:
            Sin {$ingredient->name} → reemplazo y cantidad
            No escribas introducción, numeración ni Markdown.

            {$this->describir($recipe)}
            TXT;

        $respuesta = $this->preguntar([
            ['role' => 'system', 'content' => self::REGLAS],
            ['role' => 'user', 'content' => $pregunta],
        ]);

        // Una alternativa por línea, sin viñetas ni números al comienzo.
        $alternativas = collect(preg_split('/\R/u', $respuesta))
            ->map(fn (string $linea) => trim(preg_replace('/^[\s\-\*•\d\.\)]+/u', '', $linea)))
            ->filter()
            ->take(3)
            ->values()
            ->all();

        if ($alternativas === []) {
            throw new IaNoDisponible('La IA respondió sin alternativas.');
        }

        return $alternativas;
    }

    // --- Texto de la receta para el modelo ---
    private function describir(Recipe $recipe): string
    {
        $ingredientes = $recipe->ingredients
            ->map(fn (Ingredient $i) => "{$i->name} ({$i->quantity})")
            ->join(', ');

        $pasos = collect($recipe->steps)
            ->map(fn (string $paso, int $i) => ($i + 1).'. '.$paso)
            ->join(' ');

        return <<<TXT
            Receta: {$recipe->title} ({$recipe->minutes} min, {$recipe->servings} porciones).
            Ingredientes: {$ingredientes}.
            Pasos: {$pasos}
            Alérgenos: {$this->lista($recipe->allergens)}.
            TXT;
    }

    private function lista(array $items): string
    {
        return implode(', ', $items);
    }
}
