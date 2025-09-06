<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;

// ====================
// RUTAS DE USUARIO NORMAL
// ====================

// Página principal (con carrusel y secciones)
Route::get('/', [PublicController::class, 'index'])->name('public.index');

// Vista individual de una rifa
Route::get('/rifa/{id}', [PublicController::class, 'showRifa'])->name('public.rifa');

// Vista métodos de pago
Route::get('/metodos-pago', [PublicController::class, 'metodosPago'])->name('public.metodosPago');


// ====================
// RUTAS DE ADMIN
// ====================

// Ruta del welcome para el admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

// Vista del formulario para crear rifa
Route::get('/admin/crearSorteo', [AdminController::class, 'vista'])->name('admin.crearSorteo');
// Procesar el formulario para crear rifa
Route::post('/admin/crearSorteo', [AdminController::class, 'guardar'])->name('admin.guardar');


// Listado de rifas
Route::get('/admin/listado', [AdminController::class, 'listado'])->name('admin.listado');

// Editar rifa
Route::get('/admin/editar/{id}', [AdminController::class, 'VistaEditar'])->name('admin.VistaEditar');
Route::put('/admin/editar/{id}', [AdminController::class, 'editar'])->name('admin.editar');

// Eliminar rifa
Route::delete('/admin/rifas/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

// Boletos
Route::get('/admin/rifas/{rifaId}/boletos', [AdminController::class, 'boletos'])->name('admin.boletos');
Route::get('/admin/rifas/{rifaId}/boletos/search', [AdminController::class, 'buscarBoletos'])->name('admin.buscarBoletos');
Route::post('/admin/boletos/{boletoId}/toggle', [AdminController::class, 'toggleBoleto'])->name('admin.toggleBoleto');


// ====================
// RUTAS DE USUARIOS LOGUEADOS
// ====================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
