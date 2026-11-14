<?php

use Illuminate\Support\Facades\Route;

// Rutas provisionales del asistente: Sebastián las reemplaza el sábado.
Route::middleware('auth')->group(function () {
    Route::view('/asistente', 'pendiente')->name('asistente.show');
    Route::delete('/asistente', fn () => back())->name('asistente.reset');

    Route::post('/asistente', fn () => response()->json(['error' => 'Todavía no hay IA.'], 503))
        ->name('asistente.send');

    Route::post('/recetas/{recipe}/ingredientes/{ingredient}/alternativas', fn () => response()->json(['error' => 'Todavía no hay IA.'], 503))
        ->name('recetas.alternativas');
});
