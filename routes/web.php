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
Route::get('/admin/crearSorteo', [AdminController::class, 'crear'])->name('admin.crearSorteo');
Route::post('/admin/crearSorteo', [AdminController::class, 'store'])->name('admin.store');
Route::get('/admin/listado', [AdminController::class, 'listado'])->name('admin.listado');


require __DIR__.'/auth.php';
