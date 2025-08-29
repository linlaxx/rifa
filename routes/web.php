<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas del Admin
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.dashboard');
Route::get('/admin/crearSorteo', [AdminController::class, 'vista'])->name('admin.crearSorteo');
Route::post('/admin/crearSorteo', [AdminController::class, 'guardar'])->name('admin.guardar');
Route::get('/admin/listado', [AdminController::class, 'listado'])->name('admin.listado');
// Mostrar formulario de edición
Route::get('/admin/editar/{id}', [AdminController::class, 'VistaEditar'])->name('admin.VistaEditar');
// Procesar actualización
Route::put('/admin/editar/{id}', [AdminController::class, 'editar'])->name('admin.editar');
//Eliminar rifa y boletos asociados
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
// Vista de boletos
Route::get('/admin/boletos', [AdminController::class, 'vistaBoletos'])->name('admin.VistaBoletos');


require __DIR__.'/auth.php';
