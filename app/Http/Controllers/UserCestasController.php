<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cesta;

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


}
