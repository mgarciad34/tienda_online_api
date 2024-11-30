<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para la creación de usuario exitoso.
     */
    public function test_crear_usuario_exitoso()
    {
        // Simula la llamada a la ruta de creación de usuario
        $payload = [
            'nombre' => 'John Doe',
            'email' => 'johndoe@example.com',
            'contrasena' => 'password123',
            'rol' => 'Usuario',
        ];

        // Verifica que se pueda crear el usuario y responde correctamente
        $response = $this->postJson('/api/auth/crear-usuario', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Usuario creado con éxito',
                     'usuario' => [
                         'nombre' => 'John Doe',
                         'email' => 'johndoe@example.com',
                         'rol' => 'Usuario',
                     ],
                 ]);

        // Verifica que el usuario exista en la base de datos
        $this->assertDatabaseHas('users', [
            'nombre' => 'John Doe',
            'email' => 'johndoe@example.com',
            'rol' => 'Usuario',
        ]);
    }

    /**
     * Test para la creación de usuario con datos inválidos.
     */
    public function test_crear_usuario_con_datos_invalidos()
    {
        $payload = [
            'nombre' => '', // Nombre vacío
            'email' => 'invalid-email', // Email no válido
            'contrasena' => '123', // Contraseña demasiado corta
            'rol' => 'Invalido', // Rol no permitido
        ];

        // Verifica que falle la validación
        $response = $this->postJson('/api/auth/crear-usuario', $payload);

        $response->assertStatus(422)
                 ->assertJsonStructure(['error']);
    }

    /**
     * Test para el inicio de sesión exitoso de un usuario.
     */
    public function test_login_usuario_exitoso()
    {
        // Crear un usuario de prueba
        $usuario = User::create([
            'nombre' => 'John Doe',
            'email' => 'johndoe@example.com',
            'contrasena' => Hash::make('password123'),
            'rol' => 'Usuario',
        ]);

        $payload = [
            'email' => 'johndoe@example.com',
            'contrasena' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Usuario autenticado como usuario',
                     'correo' => 'johndoe@example.com',
                     'rol' => 'Usuario',
                 ]);
    }

    /**
     * Test para el intento de inicio de sesión con credenciales inválidas.
     */
    public function test_login_credenciales_invalidas()
    {
        // Crear un usuario de prueba
        User::create([
            'nombre' => 'John Doe',
            'email' => 'johndoe@example.com',
            'contrasena' => Hash::make('password123'),
            'rol' => 'Usuario',
        ]);

        $payload = [
            'email' => 'johndoe@example.com',
            'contrasena' => 'wrongpassword', // Contraseña incorrecta
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Credenciales inválidas',
                 ]);
    }

    /**
     * Test para el intento de inicio de sesión con un usuario no existente.
     */
    public function test_login_usuario_no_existente()
    {
        $payload = [
            'email' => 'nonexistent@example.com',
            'contrasena' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Credenciales inválidas',
                 ]);
    }
}
