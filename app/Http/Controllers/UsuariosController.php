<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function actualizarUsuario(Request $request, $id)
    {
        \Log::info('Intento de actualización de usuario:', ['datos' => $request->all()]);

        // Validar que el usuario existe y tenga permisos para actualizar
        $validator = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
            'contrasena' => 'nullable|string|min:8',
            'rol' => 'nullable|string|in:Administrador,Usuario',
        ]);

        \Log::info('Validación de datos:', ['validator' => $validator]);

        if ($validator->fails()) {
            \Log::error('Validación fallida', ['errors' => $validator->errors()->all()]);
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            // Obtener el usuario existente
            $usuario = User::findOrFail($id);

            \Log::info('Usuario encontrado:', ['id' => $id, 'usuario' => $usuario]);

            // Actualizar los campos del usuario individualmente
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

                \Log::info('Actualización exitosa:', ['actualizado' => $actualizado]);

                return response()->json(['message' => 'Usuario actualizado con éxito', 'usuario' => $usuario], 200);
            } else {
                \Log::warning('No se realizaron cambios en el usuario');
                return response()->json(['error' => 'No se realizaron cambios en el usuario'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Error al actualizar usuario', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error al actualizar usuario: ' . $e->getMessage()], 422);
        }
    }
}
