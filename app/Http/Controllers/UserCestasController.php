<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cesta;
use Illuminate\Support\Facades\Log;
class UserCestasController extends Controller
{

    public function anadirCesta(Request $request)
    {
        $validatedData = $request->validate([
            'usuario_id' => ['required', 'integer', 'min:1'],
            'total' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'estado' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cesta = Cesta::create($validatedData);

            return response()->json(['message' => 'Cesta created successfully', 'data' => $cesta], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create Cesta: ' . $e->getMessage()], 422);
        }
    }

    public function cerrarCesta(int $id)
    {
        try {
            $cesta = Cesta::findOrFail($id);
            $cesta->estado = 'cerrada';
            $cesta->save();


            return response()->json([
                'message' => 'La cesta ha sido cerrada con éxito',
                'data' => $cesta
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al intentar cerrar la cesta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEstadoCesta(int $usuarioId)
    {
        try {
            $carritoAbierto = Cesta::where('usuario_id', $usuarioId)
                ->where('estado', 'abierta')
                ->orderBy('id', 'desc')
                ->first();

            if ($carritoAbierto == null) {
                echo $carritoAbierto;
                return response()->json([
                    'data' => 0
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Carrito abierto encontrado con éxito',
                    'data' => [
                        'id' => $carritoAbierto->id,
                        'estado' => $carritoAbierto->estado
                    ]
                ], 200);
            }


        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al intentar obtener el estado del carrito: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerHistorialCesta(int $usuarioId)
    {
        try {
            // Ejecuta la consulta para obtener el total y el updated_at del carrito cerrado
            $carrito = DB::table('cestas')
                ->where('usuario_id', $usuarioId)
                ->where('estado', 'cerrada')
                ->select('total', 'updated_at') // Selecciona los campos 'total' y 'updated_at'
                ->first(); // Obtiene el primer registro coincidente

            // Si no se encuentra un carrito, retorna 0
            if ($carrito === null) {
                return response()->json([
                    'data' => 0
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Carrito abierto encontrado con éxito',
                    'data' => [
                        'total' => $carrito->total,
                        'updated_at' => $carrito->updated_at
                    ]
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al intentar obtener el estado del carrito: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actualizarTotalCesta(int $usuarioId, float $nuevoTotal)
    {
        try {
            $carrito = DB::table('cestas')
                ->where('usuario_id', $usuarioId)
                ->where('estado', 'cerrada')
                ->first();

            if ($carrito === null) {
                return response()->json([
                    'error' => 'No se encontró un carrito cerrado para este usuario.'
                ], 404);
            }

            // Asegurarse de que $nuevoTotal es un número flotante
            $nuevoTotalFloat = floatval($nuevoTotal);

            DB::table('cestas')
                ->where('id', $carrito->id)
                ->update(['total' => $nuevoTotalFloat, 'updated_at' => now()]);

            return response()->json([
                'message' => 'El total de la cesta ha sido actualizado con éxito.',
                'data' => [
                    'total' => $nuevoTotalFloat
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al intentar actualizar el total de la cesta: ' . $e->getMessage()
            ], 500);
        }
    }
}
