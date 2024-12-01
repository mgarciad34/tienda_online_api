<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UserCestasController;
use App\Http\Controllers\UserDetallesCesta;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RecomendacionesController;
use App\Http\Controllers\UsuariosController;
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

Route::post('/recuperar', [AuthController::class, 'recuperarCorreo']);

//Rutas Administrador
Route::group(['prefix' => '/admin', 'middleware' => ['auth:sanctum', 'admin']], function () {
    Route::post('/categorias', [AdminCategoriasController::class, 'crearCategoria']);
    Route::get('/categorias', [AdminCategoriasController::class, 'obtenerCategorias']);
    Route::get('/categorias/{id}', [AdminCategoriasController::class, 'obtenerCategoriaId']);
    Route::put('/categorias/{id}', [AdminCategoriasController::class, 'actualizarCategoria']);
    Route::delete('/categorias/{id}', [AdminCategoriasController::class, 'eliminarCategoria']);

    // Rutas Productos
    Route::post('/productos', [ProductosController::class, 'crearProducto']);
    Route::get('/productos', [ProductosController::class, 'obtenerProductos']);
    Route::get('/productos/{nombre}', [ProductosController::class, 'obtenerProductosNombre']);
    Route::put('/productos/{id}', [ProductosController::class, 'actualizarProducto']);
    Route::delete('/productos/{id}', [ProductosController::class, 'eliminarProducto']);
});


//Rutas usuario
Route::group(['prefix' => '/usuario', 'middleware' => ['auth:sanctum', 'usuario']], function () {
    Route::put('/actualizar/{id}', [UsuariosController::class, 'actualizarUsuario']);

    Route::get('/productos', [ProductosController::class, 'obtenerProductos']);
    Route::get('/productos/{id}', [ProductosController::class, 'obtenerProductosId']);
    Route::get('/obtener/{id}', [AuthController::class, 'obtenerUsuario']);

    Route::post('/anadir/cesta',[UserCestasController::class, 'anadirCesta']);
    Route::put('/cerrar/cesta/{id}', [UserCestasController::class, 'cerrarCesta']);
    Route::get('/cesta/{id}', [UserDetallesCesta::class, 'obtenerCestaId']);
    Route::get('/carrito/{id}', [UserDetallesCesta::class, 'obtenerCarritoId']);

    Route::get('/obtener/estado/cesta/{usuarioId}',[UserCestasController::class, 'obtenerEstadoCesta']);
    Route::post('/agregar/producto', [UserDetallesCesta::class, 'insertarProducto']);
    Route::put('/actualizar/producto/{id}', [UserDetallesCesta::class, 'actualizarProducto']);
    Route::delete('/eliminar/producto/{id}', [UserDetallesCesta::class, 'eliminarProducto']);

    Route::post('/stripe', [PaymentController::class, 'createPaymentIntent']);
    Route::get('/productos/recomendaciones/{usuarioId}', [RecomendacionesController::class, 'crearRecomendaciones']);
});
