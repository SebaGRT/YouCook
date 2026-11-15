<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Perfil: datos, progreso del reto, stickers y checklist.
    public function show(Request $request): View
    {
        $user = $request->user();
        $recipes = Recipe::orderBy('id')->get(['id', 'slug', 'title', 'sticker', 'sticker_name']);
        $cookedIds = $user->cookedRecipes()->pluck('recipes.id')->all();
        $cooked = count($cookedIds);

        return view('perfil.show', [
            'user' => $user,
            'recipes' => $recipes,
            'cookedIds' => $cookedIds,
            'cooked' => $cooked,
            'saved' => $user->savedRecipes()->count(),
            'total' => $recipes->count(),
            'level' => match (true) {
                $cooked >= 20 => 'Maestra de cocina',
                $cooked >= 10 => 'Chef de pensión',
                $cooked >= 5 => 'Aprendiz',
                default => 'Cocinera en entrenamiento',
            },
        ]);
    }

    // Guarda el nombre y el correo (formulario «Mis datos»).
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $datos = $request->validate([
            'name' => 'required|string|max:255',
            // Único entre los usuarios, salvo el mío: si no cambio el correo, no choca conmigo.
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($datos);

        return redirect()->route('perfil.show')->with('toast', [
            'title' => 'Datos actualizados',
            'body' => 'Guardamos tu nombre y tu correo.',
        ]);
    }
}