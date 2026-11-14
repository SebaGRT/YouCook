<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Las cuentas. Con middleware guest.
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar']);
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');

    // 6 intentos por minuto
    Route::post('/login', [AuthController::class, 'entrar'])->middleware('throttle:6,1');
});

Route::post('/logout', [AuthController::class, 'salir'])->middleware('auth')->name('logout');
