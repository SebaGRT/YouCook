<?php

use App\Http\Controllers\CookedRecipeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SavedRecipeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/recetas');

Route::middleware('auth')->group(function () {
    Route::get('/recetas', [RecipeController::class, 'index'])->name('recetas.index');
    Route::get('/recetas/{recipe}', [RecipeController::class, 'show'])->name('recetas.show');
    Route::post('/recetas/{recipe}/guardar', [SavedRecipeController::class, 'toggle'])->name('recetas.guardar');
    Route::post('/recetas/{recipe}/cocinada', [CookedRecipeController::class, 'toggle'])->name('recetas.cocinada');

    Route::get('/guardados', [SavedRecipeController::class, 'index'])->name('guardados.index');

    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil.show');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
});

// Página de pruebas visuales, solo en tu computador: crea
// resources/views/muestra.blade.php (está en .gitignore y no se sube).
if (app()->isLocal()) {
    Route::view('/muestra', 'muestra')->middleware('auth');
}

require __DIR__.'/ia.php';
require __DIR__.'/auth.php';