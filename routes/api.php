<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\ProductosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/registro', [AuthController::class, 'crearUsuario']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Rutas Administrador
    Route::post('/categorias', [AdminCategoriasController::class, 'crearCategoria']);
    Route::get('/categorias', [AdminCategoriasController::class, 'obtenerCategorias']);
    Route::get('/categorias/{id}', [AdminCategoriasController::class, 'obtenerCategoriaId']);
    Route::put('/categorias/{id}', [AdminCategoriasController::class, 'actualizarCategoria']);
    Route::delete('/categorias/{id}', [AdminCategoriasController::class, 'eliminarCategoria']);

//Rutas Productos
Route::post('/productos', [ProductosController::class, 'crearProducto']);
