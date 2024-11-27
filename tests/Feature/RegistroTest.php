<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    /*******************************************************
    Pruebas creadas
    - Creación de un usuario
    - Creacion e un usuario con un correo ya registrado
     ******************************************************ff*/
    use RefreshDatabase;

    /**
     * @test
     */
    public function usuario_creado_con_valores_validos()
    {
        $request = [
            'nombre' => 'John Doe',
            'email' => 'john@example.com',
            'contrasena' => 'password123',
            'rol' => 'Administrador'
        ];

        $response = $this->post('http://localhost:8000/api/registro', $request);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message' => ['Usuario Creado'],
            'usuario' => [
                'nombre',
                'contrasena',
                'email',
                'rol'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'nombre' => 'John Doe',
            'email' => 'john@example.com',
            'contrasena' => 'ontraseña123',
            'rol' => 'Usuario'
        ]);
    }

    /**
     * @test
     */
    public function usuarioEmailCreado()
    {
        $request = [
            'nombre' => 'John Doe',
            'email' => 'john@example.com',
            'contrasena' => 'ontraseña123',
            'rol' => 'Usuario'
        ];

        \App\Models\User::create($request);

        $response = $this->post('http://localhost:8000/api/registro', $request);

        $response->assertStatus(201);
        $response->assertJsonPath('error', 'Email ya registrado');
    }


}
