<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/recetas');

Route::middleware('auth')->group(function () {
    Route::get('/recetas', [RecipeController::class, 'index'])->name('recetas.index');
    Route::get('/recetas/{recipe}', [RecipeController::class, 'show'])->name('recetas.show');

    // Provisionales: se reemplazan mañana por sus controladores.
    Route::post('/recetas/{recipe}/guardar', fn () => back())->name('recetas.guardar');
    Route::post('/recetas/{recipe}/cocinada', fn () => back())->name('recetas.cocinada');

    Route::view('/guardados', 'pendiente')->name('guardados.index');

    Route::view('/perfil', 'pendiente')->name('perfil.show');
    Route::patch('/perfil', fn () => back())->name('perfil.update');
});

// Página de pruebas visuales, solo en tu computador: crea
// resources/views/muestra.blade.php (está en .gitignore y no se sube).
if (app()->isLocal()) {
    Route::view('/muestra', 'muestra')->middleware('auth');
}

require __DIR__.'/ia.php';
require __DIR__.'/auth.php';
