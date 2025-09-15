<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Mostrar el formulario de login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Procesar login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Bloquear cualquier otra ruta de registro o recuperación
Route::any('/register', fn() => abort(404));
Route::any('/forgot-password', fn() => abort(404));
Route::any('/reset-password/{token}', fn() => abort(404));
