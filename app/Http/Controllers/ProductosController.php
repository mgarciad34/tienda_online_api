<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\File;

class ProductosController extends Controller
{
    public function crearProducto(Request $request)
    {

        // Validar la entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0|gt:0',
            'existencias' => 'required|integer',
            'categoria_id' => 'required|integer',
            'img1' => 'required|string',
            'img2' => 'required|string',
            'img3' => 'required|string',
        ]);

        try {
            // Crear el producto con las URLs base64 de las imágenes
            $producto = Producto::create([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'precio' => $request->input('precio'),
                'existencias' => $request->input('existencias'),
                'categoria_id' => $request->input('categoria_id'),
                'img1' => $request->input('img1'),
                'img2' => $request->input('img2'),
                'img3' => $request->input('img3'),
            ]);

            return response()->json(['message' => 'Producto creado exitosamente', 'producto' => $producto], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear el producto', 'error' => $e->getMessage()], 500);
        }
    }

    private function convertToBase64($path)
    {
        $imageData = File::get($path);
        $base64 = base64_encode($imageData);
        return 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . $base64;
    }


    public function obtenerProductos(Request $request)
    {
        try {
            $productos = Producto::all();

            return response()->json([
                'mensaje' => 'Productos obtenidos',
                'productos' => $productos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al obtener los productos: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function obtenerProductosNombre($nombre)
    {
        try {
            $productos = $nombre ? Producto::where('nombre', 'like', '%' . $nombre . '%')->get() : Producto::all();

            return response()->json([
                'mensaje' => 'Productos obtenidos',
                'productos' => $productos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al obtener los productos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function obtenerProductosId($id)
    {
        try {
            $producto = $id ? Producto::findOrFail($id) : null;

            return response()->json([
                'mensaje' => 'Producto obtenido',
                'producto' => $producto,
            ], $producto ? 200 : 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al obtener el producto: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function actualizarProducto(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'img1' => 'sometimes|string',
                'img2' => 'nullable|string',
                'img3' => 'nullable|string',
                'descripcion' => 'sometimes|string|max:1000',
                'precio' => 'sometimes|numeric|min:0|gt:0',
                'existencias' => 'sometimes|integer|min:0',
                'categoria_id' => 'sometimes|integer|exists:categorias,id'
            ]);

            foreach (['img1', 'img2', 'img3'] as $imgAttribute) {
                if (!empty($validatedData[$imgAttribute]) && !str_starts_with($validatedData[$imgAttribute], 'data:image/')) {
                    $validatedData[$imgAttribute] = base64_encode($validatedData[$imgAttribute]);
                }
            }

            $producto = Producto::findOrFail($id);

            $producto->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'El producto ha sido actualizado exitosamente',
                'productId' => $producto->id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al actualizar el producto: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function eliminarProducto(Request $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);

            $producto->delete();

            return response()->json([
                'mensaje' => 'El producto ha sido eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al eliminar el producto: ' . $e->getMessage(),
            ], 500);
        }
    }

}
