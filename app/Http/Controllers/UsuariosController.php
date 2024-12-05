<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function actualizarUsuario(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
            'contrasena' => 'nullable|string|min:8',
            'rol' => 'nullable|string|in:Administrador,Usuario',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $usuario = User::findOrFail($id);
            $actualizado = false;

            if ($request->nombre) {
                $usuario->nombre = $request->nombre;
                $actualizado = true;
            }

            if ($request->email) {
                $usuario->email = $request->email;
                $actualizado = true;
            }

            if ($request->contrasena) {
                $usuario->contrasena = bcrypt($request->contrasena);
                $actualizado = true;
            }

            if ($request->rol) {
                $usuario->rol = $request->rol;
                $actualizado = true;
            }

            if ($actualizado) {
                $usuario->updated_at = now();
                $usuario->save();

                return response()->json(['message' => 'Usuario actualizado con éxito', 'usuario' => $usuario], 200);
            } else {
                return response()->json(['error' => 'No se realizaron cambios en el usuario'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar usuario: ' . $e->getMessage()], 422);
        }
    }
}
