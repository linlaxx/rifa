<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminNumeroController;

// ====================
// RUTAS PÚBLICAS
// ====================

// Página principal (con carrusel y secciones)
Route::get('/', [PublicController::class, 'index'])->name('public.index');

// Vista individual de una rifa
Route::get('/rifa/{id}', [PublicController::class, 'showRifa'])->name('public.rifa');

// Ruta AJAX para traer boletos por página (público)
Route::get('/rifa/{id}/boletos', [PublicController::class, 'boletosPorPagina'])->name('public.rifa.boletos');

// Ruta AJAX para traer boletos disponibles
Route::get('/rifa/{id}/boletos-disponibles', [PublicController::class, 'boletosDisponibles']);

// Vista métodos de pago
Route::get('/metodos-pago', [PublicController::class, 'metodosPago'])->name('public.metodosPago');

// Procesar la reserva de boletos
Route::post('/rifa/reservar', [PublicController::class, 'reservar'])->name('rifa.reservar');


// ====================
// RUTAS ADMIN (requieren login + admin)
// ====================

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Gestión de números
    Route::get('numeros', [AdminNumeroController::class, 'index'])->name('admin.numeros.index');
    Route::post('numeros', [AdminNumeroController::class, 'store'])->name('admin.numeros.store');
    Route::delete('numeros/{id}', [AdminNumeroController::class, 'destroy'])->name('admin.numeros.destroy');
    Route::get('numeros/mezclar', [AdminNumeroController::class, 'mezclar'])->name('admin.numeros.mezclar');

    // Rifas
    Route::get('crear-sorteo', [AdminController::class, 'vista'])->name('admin.crearSorteo');
    Route::post('crear-sorteo', [AdminController::class, 'guardar'])->name('admin.guardar');
    Route::get('listado', [AdminController::class, 'listado'])->name('admin.listado');
    Route::get('editar/{id}', [AdminController::class, 'VistaEditar'])->name('admin.VistaEditar');
    Route::put('editar/{id}', [AdminController::class, 'editar'])->name('admin.editar');
    Route::delete('rifas/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Boletos
    Route::get('rifas/{rifaId}/boletos', [AdminController::class, 'boletos'])->name('admin.boletos');
    Route::get('rifas/{rifaId}/boletos/search', [AdminController::class, 'buscarBoletos'])->name('admin.buscarBoletos');
    Route::post('boletos/{boletoId}/toggle', [AdminController::class, 'toggleBoleto'])->name('admin.toggleBoleto');
});

// ====================
// AUTENTICACIÓN (solo login/logout)
// ====================

require __DIR__ . '/auth.php';
