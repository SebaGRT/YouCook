<?php

namespace App\Http\Controllers;

use App\Exceptions\IaNoDisponible;
use App\Models\Recipe;
use App\Services\AsistenteIA;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssistantController extends Controller
{
    // La conversación vive en la sesión:
    //   asistente.receta_id → receta de la que se está hablando (o null)
    //   asistente.mensajes  → [['role' => 'user'|'assistant', 'content' => '...'], ...]

    // Pantalla del chat: /asistente o /asistente?receta=charquican
    public function show(Request $request): View
    {
        $recipe = Recipe::where('slug', $request->query('receta'))->first();

        // Llegar desde otra receta empieza una conversación nueva.
        if ($recipe && $recipe->id !== session('asistente.receta_id')) {
            session(['asistente' => ['receta_id' => $recipe->id, 'mensajes' => []]]);
        }

        $recipe ??= Recipe::find(session('asistente.receta_id'));

        return view('asistente.show', [
            'recipe' => $recipe,
            'mensajes' => session('asistente.mensajes', []),
            'sugerencias' => $recipe
                ? ['¿Cómo sé si está cocido?', '¿Puedo congelarlo?', '¿Con qué lo acompaño?', '¿Lo puedo hacer sin carne?']
                : ['¿Qué hago con arroz, huevo y cebolla?', '¿Cuánto dura el arroz en el refri?', 'Se me pasó de sal, ¿cómo lo arreglo?'],
        ]);
    }

    // Recibe un mensaje y responde JSON con la respuesta de la IA.
    public function send(Request $request, AsistenteIA $ia): JsonResponse
    {
        $datos = $request->validate([
            'mensaje' => ['required', 'string', 'max:500'],
        ]);

        $historial = session('asistente.mensajes', []);
        $historial[] = ['role' => 'user', 'content' => $datos['mensaje']];

        try {
            $respuesta = $ia->conversar(
                array_slice($historial, -10),   // solo los últimos mensajes: menos tokens
                Recipe::with('ingredients')->find(session('asistente.receta_id')),
                $request->user()->first_name,
            );
        } catch (IaNoDisponible) {
            // No se guarda nada: «Reintentar» vuelve a enviar la misma pregunta.
            return response()->json(['error' => 'El asistente no está disponible.'], 503);
        }

        $historial[] = ['role' => 'assistant', 'content' => $respuesta];
        session(['asistente.mensajes' => array_slice($historial, -30)]);

        return response()->json(['respuesta' => $respuesta]);
    }

    // Botón «Nueva conversación»: borra los mensajes y conserva la receta.
    public function reset(): RedirectResponse
    {
        session()->forget('asistente.mensajes');

        return redirect()->route('asistente.show');
    }
}
