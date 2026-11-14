<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    // Catálogo: /recetas?q=huevo&categoria=Rápidas&tiempo=30&dificultad=Fácil
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:60'],
            'categoria' => ['nullable', 'string'],
            'tiempo' => ['nullable', 'integer'],
            'dificultad' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        return view('recetas.index', [
            'recipes' => Recipe::filter($filters)->orderBy('id')->paginate(8)->withQueryString(),
            'filters' => $filters,
            'total' => Recipe::count(),
            'savedIds' => $user->savedRecipes()->pluck('recipes.id')->all(),
            'cookedIds' => $user->cookedRecipes()->pluck('recipes.id')->all(),
        ]);
    }

    // Detalle: /recetas/charquican
    public function show(Request $request, Recipe $recipe): View
    {
        $user = $request->user();

        return view('recetas.show', [
            'recipe' => $recipe->load('ingredients'),
            'isSaved' => $user->hasSaved($recipe),
            'isCooked' => $user->hasCooked($recipe),
        ]);
    }
}