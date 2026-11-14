<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/recetas');

// Rutas provisionales: existen desde el primer día para que los enlaces y los
// formularios de todos funcionen. Miguel reemplaza cada una por su controlador.
Route::middleware('auth')->group(function () {
    Route::view('/recetas', 'pendiente')->name('recetas.index');
    Route::view('/recetas/{recipe}', 'pendiente')->name('recetas.show');
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
