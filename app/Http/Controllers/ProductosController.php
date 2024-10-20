<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductosController extends Controller
{
    public function crearProducto(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'img1' => 'required|string',
            'img2' => 'nullable|string',
            'img3' => 'nullable|string',
            'descripcion' => 'required|string|max:1000',
            'precio' => 'required|numeric',
            'existencias' => 'required|integer|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id'
        ]);

        // Convertir las imágenes a base64 solo si no empiezan con "data:image/"
        foreach (['img1', 'img2', 'img3'] as $imgAttribute) {
            if (!empty($validatedData[$imgAttribute]) && !str_starts_with($validatedData[$imgAttribute], 'data:image/')) {
                $validatedData[$imgAttribute] = base64_encode($validatedData[$imgAttribute]);
            }
        }

        $producto = Producto::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'productId' => $producto->id
        ], 200);
    }


}
