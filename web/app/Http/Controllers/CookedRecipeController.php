<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CookedRecipeController extends Controller
{
    // Marca la receta como cocinada o, si ya lo estaba, la desmarca.
    public function toggle(Request $request, Recipe $recipe): RedirectResponse
    {
        $user = $request->user();
        $changes = $user->cookedRecipes()->toggle($recipe);

        // Solo hay sticker nuevo cuando se marca, no cuando se desmarca.
        if ($changes['attached'] === []) {
            return back();
        }

        return back()->with('toast', [
            'title' => '¡Receta cocinada!',
            'body' => sprintf(
                'Desbloqueaste el sticker «%s» · %d de %d',
                $recipe->sticker_name,
                $user->cookedRecipes()->count(),
                Recipe::count(),
            ),
            'sticker' => $recipe->sticker,
        ]);
    }
}