<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\UserCestasController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{

    protected $cestasController;
    public function __construct(UserCestasController $cestasController)
    {
        $this->cestasController = $cestasController;
    }


    public function fncCrearUsuario(Request $request)
    {
        // Validar los datos de entrada
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
            // Crear el usuario
            $usuario = User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'contrasena' => bcrypt($request->contrasena),
                'rol' => $request->rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Crear la cesta asociada al usuario
            $cestaData = [
                'usuario_id' => $usuario->id,
                'total' => 0,
                'estado' => 'abierta'
            ];

            // Validar datos antes de enviar a anadirCesta
            $validatorCesta = Validator::make($cestaData, [
                'usuario_id' => ['required', 'integer', 'min:1'],
                'total' => ['required', 'numeric', 'min:0', 'max:10000000'],
                'estado' => ['required', 'string', 'max:255'],
            ]);

            if ($validatorCesta->fails()) {
                return response()->json(['error' => 'Validación de cesta fallida: ' . $validatorCesta->errors()->first()], 422);
            }

            // Llamar al método del controlador para crear la cesta
            $this->cestasController->anadirCesta(new Request($cestaData));

            return response()->json(['message' => 'Usuario creado con éxito', 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            // Manejar errores durante la creación
            return response()->json(['error' => 'Error al crear usuario o cesta: ' . $e->getMessage()], 422);
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


    public function recuperarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $correoDestino = $request->input('email');

        $usuario = User::where('email', $correoDestino)->first();

        if (!$usuario) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }

        $nuevaContrasena = "123456";
        $usuario->contrasena = bcrypt($nuevaContrasena);

        $usuario->save();

        try {
            Mail::raw('', function ($message) use ($correoDestino, $nuevaContrasena) {
                $body = new \Symfony\Component\Mime\Part\TextPart("
Estimado/a cliente,

Le informamos que hemos recibido una solicitud de recuperación de contraseña para su cuenta en nuestra tienda online. A continuación, le adjuntamos la nueva contraseña temporal:

Nueva contraseña: {$nuevaContrasena}

Por favor, cambie esta contraseña en el menú de ajustes de su cuenta lo antes posible para garantizar la seguridad de su información personal.

Atentamente,
El equipo de soporte de nuestra tienda online
");

                $message->to($correoDestino)
                    ->subject('Recuperación de contraseña - Nuestra Tienda Online')
                    ->setBody($body);
            });


            return response()->json(['status' => 'success', 'message' => 'Correo enviado exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al enviar el correo. Por favor, intenta nuevamente.', 500]);
        }
    }

}
