<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', [ProductoController::class, 'index'])->name('inicio');

Route::get('/productos/{id}', [ProductoController::class, 'show']);

Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');

Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');


Route::post('/guardar-producto', [ProductoController::class, 'guardarProducto'])->name('guardar.producto');
Route::post('/guardar-detalles', [ProductoController::class, 'guardarDetalles'])->name('guardar.detalles');
Route::post('/guardar-color', [ProductoController::class, 'guardarColor'])->name('guardar.color');

Route::get('/agregar-producto', [ProductoController::class, 'mostrarFormularioAgregar'])->name('agregar.producto');

Route::get('/obtener-datos-resumen/{id}', [ProductoController::class, 'obtenerDatosResumen']);

Route::post('/finalizar-proceso', [ProductoController::class, 'finalizarProceso']);