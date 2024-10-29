<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;

class AdminCategoriasController extends Controller
{
    public function fncCrearCategoria(Request $request){
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        try{
            $categoria = Categoria::create($validatedData);
            return response()->json(['message' => 'Categoria creada', 'categoria' => $categoria], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la categoria: ' . $e->getMessage()], 422);
        }
    }

    public function fncObtenerCategorias(Request $request)
{
    try {
        $categorias = Categoria::select('id', 'nombre')->get();

        return response()->json([
            'mensaje' => 'Categorías obtenidas',
            'categorias' => $categorias,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Ocurrió un error al obtener las categorías: ' . $e->getMessage(),
        ], 500);
    }
}


    public function fncObtenerCategoriaId($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            return response()->json([
                'mensaje' => 'Categoria obtenida con éxito',
                'categoria' => $categoria,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se encontró la categoría con el ID especificado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al obtener la categoría: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function fncActualizarCategoria(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $categoria = Categoria::findOrFail($id);

            $categoria->update($validatedData);

            return response()->json([
                'mensaje' => 'Categoria actualizada con éxito',
                'categoria' => $categoria,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se encontró la categoría con el ID especificado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al actualizar la categoría: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function fncEliminarCategoria($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            $categoria->delete();

            return response()->json([
                'mensaje' => 'Categoria eliminada con éxito',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se encontró la categoría con el ID especificado',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al eliminar la categoría: ' . $e->getMessage(),
            ], 500);
        }
    }

}
