<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedRecipeController extends Controller
{
    // Guardados: /guardados
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('guardados.index', [
            'recipes' => $user->savedRecipes()->orderBy('saved_recipes.created_at')->get(),
            'cookedIds' => $user->cookedRecipes()->pluck('recipes.id')->all(),
        ]);
    }

    // Guarda la receta o, si ya estaba guardada, la quita.
    public function toggle(Request $request, Recipe $recipe): RedirectResponse
    {
        $request->user()->savedRecipes()->toggle($recipe);

        return back()->withFragment('receta-'.$recipe->slug);
    }
}