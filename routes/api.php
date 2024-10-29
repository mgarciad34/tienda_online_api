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
Route::post('/registro', [AuthController::class, 'fncCrearUsuario']);

Route::post('/login', [AuthController::class, 'fncLogin'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'fncLogout']);
});

//Rutas Administrador
Route::group(['prefix' => '/admin', 'middleware' => ['auth:sanctum', 'admin']], function () {
    Route::post('/categorias', [AdminCategoriasController::class, 'fncCrearCategoria']);
    Route::get('/categorias', [AdminCategoriasController::class, 'fncObtenerCategorias']);
    Route::get('/categorias/{id}', [AdminCategoriasController::class, 'fncObtenerCategoriaId']);
    Route::put('/categorias/{id}', [AdminCategoriasController::class, 'fncActualizarCategoria']);
    Route::delete('/categorias/{id}', [AdminCategoriasController::class, 'fncEliminarCategoria']);

    // Rutas Productos
    Route::post('/productos', [ProductosController::class, 'fncCrearProducto']);
    Route::get('/productos', [ProductosController::class, 'fncObtenerProductos']);
    Route::get('/productos/{nombre}', [ProductosController::class, 'fncObtenerProductosNombre']);
    Route::put('/productos/{id}', [ProductosController::class, 'fncActualizarProducto']);
    Route::delete('/productos/{id}', [ProductosController::class, 'fncEliminarProducto']);
});


//Rutas Administrador
Route::group(['prefix' => '/usuario', 'middleware' => ['auth:sanctum', 'usuario']], function () {
    Route::get('/productos', [ProductosController::class, 'fncObtenerProductos']);
    Route::put('/productos/{id}', [ProductosController::class, 'fncActualizarProducto']);
});
