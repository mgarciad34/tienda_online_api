<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CestaDetalle;
use Illuminate\Support\Facades\DB;


class UserDetallesCesta extends Controller
{
    public function insertarProducto(Request $request)
    {
        $validatedData = $request->validate([
            'cesta_id' => 'required|exists:cestas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $cestaDetalle = CestaDetalle::create($validatedData);

        if ($cestaDetalle) {
            return response()->json(['message' => 'Producto agregado correctamente', 'data' => $cestaDetalle], 200);
        } else {
            return response()->json(['message' => 'Error al agregar el producto'], 500);
        }
    }

    public function obtenerCestaId($id)
    {
        $cestaDetalles = CestaDetalle::where('cesta_id', $id)->get();

        if ($cestaDetalles->isNotEmpty()) {
            return response()->json(['message' => 'Cesta detalles obtenidos correctamente', 'data' => $cestaDetalles], 200);
        } else {
            return response()->json(['message' => 'No se encontraron cesta detalles para la cesta especificada'], 404);
        }
    }

    public function obtenerCarritoId($id)
    {
        $carrito = DB::table('cesta_detalles')
            ->join('productos', 'cesta_detalles.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                'productos.img1',
                'productos.descripcion',
                'cesta_detalles.cantidad',
                'cesta_detalles.subtotal'
            )
            ->where('cesta_detalles.cesta_id', $id)
            ->get();

        return response()->json($carrito);
    }



    public function actualizarProducto(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cesta_id' => 'required|exists:cestas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $cestaDetalle = CestaDetalle::findOrFail($id);

        $cestaDetalle->update([
            'cesta_id' => $validatedData['cesta_id'],
            'producto_id' => $validatedData['producto_id'],
            'cantidad' => $validatedData['cantidad'],
            'precio_unitario' => $validatedData['precio_unitario'],
            'subtotal' => $validatedData['subtotal']
        ]);

        return response()->json(['message' => 'Producto actualizado correctamente', 'data' => $cestaDetalle], 200);
    }

    public function eliminarProducto($id)
    {
        $cestaDetalle = CestaDetalle::findOrFail($id);

        try {
            $cestaDetalle->delete();
            return response()->json(['message' => 'Cesta detalle eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar la cesta detalle'], 500);
        }
    }



}
