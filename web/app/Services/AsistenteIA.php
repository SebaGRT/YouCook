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
}
