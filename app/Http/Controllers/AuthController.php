<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function fncCrearUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contrasena' => 'required|string|min:8',
            'rol' => 'required|string|in:Administrador,Usuario',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $usuario = User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'contrasena' => bcrypt($request->contrasena),
                'rol' => $request->rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Usuario creado con éxito', 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear usuario: ' . $e->getMessage()], 422);
        }
    }


    public function fncLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'contrasena' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $usuario = User::where('email', $request->email)->first();

            if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
                throw new \Exception('Credenciales inválidas');
            }

            if ($usuario->rol === 'administrador') {
                $token = $usuario->createToken('token_admin')->plainTextToken;
                return response()->json([
                    'message' => 'Usuario autenticado como administrador',
                    'token' => $token,
                    'correo' => $usuario->email,
                    'rol' => 'Administrador'
                ], 200);
            } else if ($usuario->rol === 'usuario') {
                $token = $usuario->createToken('token_usuario')->plainTextToken;
                return response()->json([
                    'message' => 'Usuario autenticado como usuario',
                    'token' => $token,
                    'correo' => $usuario->email,
                    'rol' => 'Usuario'
                ], 200);
            }

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
            return response()->json(['error' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }





    public function fncLogout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso'], 200);
    }


}
