<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\IngredientAlternativeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/asistente', [AssistantController::class, 'show'])->name('asistente.show');
    Route::delete('/asistente', [AssistantController::class, 'reset'])->name('asistente.reset');

    // Las dos rutas que llaman a la IA: máximo 20 consultas por minuto por persona.
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('/asistente', [AssistantController::class, 'send'])->name('asistente.send');

        Route::post('/recetas/{recipe}/ingredientes/{ingredient}/alternativas', IngredientAlternativeController::class)
            ->scopeBindings()
            ->name('recetas.alternativas');
    });
});
