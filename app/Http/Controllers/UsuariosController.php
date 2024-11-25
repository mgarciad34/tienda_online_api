<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Log;

class UsuariosController extends Controller
{
    public function actualizarUsuario(Request $request, $id)
    {

        try {
            $data = $request->all();

            $usuario = User::find($id);

            if (!$usuario) {
                Log::error('Usuario no encontrado', ['id' => $id]);
                return response()->json([
                    'message' => 'El usuario no fue encontrado',
                    'status' => 404
                ], 404);
            }

            $actualizado = $usuario->update(array_filter($data));

            if ($actualizado) {
                return response()->json([
                    'message' => 'El usuario fue actualizado exitosamente',
                    'status' => 200,
                    'data' => $usuario
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error al actualizar el usuario',
                    'status' => 500
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error inesperado',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
