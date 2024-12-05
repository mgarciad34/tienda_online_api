<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CestaDetalle;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
                'cesta_detalles.*'
            )
            ->where('cesta_detalles.cesta_id', $id)
            ->get();

        return response()->json($carrito);
    }

    public function actualizarProducto(Request $request, $id)
    {
        Log::info("Intento de actualización de cesta detalle", [
            'cesta_id' => $request->cesta_id,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            'subtotal' => $request->subtotal,
            'id' => $id
        ]);

        try {
            $validatedData = $request->validate([
                'cesta_id' => 'required|exists:cestas,id',
                'producto_id' => 'required|exists:productos,id',
                'cantidad' => 'required|numeric|min:1',
                'precio_unitario' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $consultaDatos = CestaDetalle::where('cesta_id', $validatedData['cesta_id'])
                             ->where('producto_id', $validatedData['producto_id'])
                             ->where('id', '!=', $id)
                             ->exists();

            if ($consultaDatos) {
                Log::warning("Intento de actualización de un producto que ya existe en la cesta", [
                    'cesta_id' => $validatedData['cesta_id'],
                    'producto_id' => $validatedData['producto_id'],
                    'id' => $id
                ]);
                throw ValidationException::withMessages([
                    'error' => 'Este producto ya está en la cesta',
                ]);
            }
            $cestaDetalle = CestaDetalle::findOrFail($id);

            $cestaDetalle->update([
                'cesta_id' => $validatedData['cesta_id'],
                'producto_id' => $validatedData['producto_id'],
                'cantidad' => $validatedData['cantidad'],
                'precio_unitario' => $validatedData['precio_unitario'],
                'subtotal' => $validatedData['subtotal']
            ]);

            Log::info("Actualización exitosa de cesta detalle", [
                'cesta_id' => $cestaDetalle->cesta_id,
                'producto_id' => $cestaDetalle->producto_id,
                'cantidad' => $cestaDetalle->cantidad,
                'precio_unitario' => $cestaDetalle->precio_unitario,
                'subtotal' => $cestaDetalle->subtotal,
                'id' => $id
            ]);

            return response()->json(['message' => 'Producto actualizado correctamente', 'data' => $cestaDetalle], 200);
        } catch (ModelNotFoundException $e) {
            Log::error("Registro no encontrado durante la actualización de cesta detalle", [
                'id' => $id
            ]);
            return response()->json(['error' => 'Registro no encontrado'], 404);
        } catch (ValidationException $e) {
            Log::error("Validación fallida durante la actualización de cesta detalle", [
                'cesta_id' => $request->cesta_id,
                'producto_id' => $request->producto_id,
                'id' => $id
            ]);
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado durante la actualización de cesta detalle", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'id' => $id
            ]);
            return response()->json(['error' => 'Ocurrió un error al actualizar el registro'], 500);
        }
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
