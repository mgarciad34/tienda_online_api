<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cesta;
use Illuminate\Validation\Rule;

class UserCestasController extends Controller
{
    public function crearCesta(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'usuario_id' => ['required', 'exists:users,id'],
            'total' => ['required', 'numeric'],
            'estado' => ['required', Rule::in(['abierta', 'cerrada'])],
        ]);

        // Create a new Cesta instance
        $cesta = Cesta::create($validatedData);

        // Return a success response
        return response()->json([
            'message' => 'Producto añadido a la cesta',
            'data' => $cesta,
        ], 201);
    }
}
