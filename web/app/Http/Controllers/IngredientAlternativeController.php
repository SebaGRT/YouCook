<?php

namespace App\Http\Controllers;

use App\Exceptions\IaNoDisponible;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Services\AsistenteIA;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class IngredientAlternativeController extends Controller
{
    // Botón «No lo tengo»: responde JSON con los reemplazos que sugiere la IA.
    public function __invoke(Recipe $recipe, Ingredient $ingredient, AsistenteIA $ia): JsonResponse
    {
        try {
            // La misma pregunta da la misma respuesta: se guarda una semana
            // para no gastar el límite gratis de la API.
            $alternativas = Cache::remember(
                "alternativas:{$ingredient->id}",
                now()->addWeek(),
                fn () => $ia->alternativas($recipe, $ingredient),
            );
        } catch (IaNoDisponible) {
            return response()->json(['error' => 'El asistente no está disponible.'], 503);
        }

        return response()->json(['alternativas' => $alternativas]);
    }
}
