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
            'total' => ['required', 'numeric', 'min:0', 'max:1000'],
            'estado' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $cesta = Cesta::create($validatedData);

            return response()->json(['message' => 'Cesta created successfully', 'data' => $cesta],  201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create Cesta: ' . $e->getMessage()], 422);
        }
    }
}
