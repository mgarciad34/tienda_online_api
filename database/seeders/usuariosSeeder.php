<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class usuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'nombre' => 'Administrador',
            'email' => 'administrador@tienda.com',
            'contrasena' => bcrypt('Admintiend@123'),
            'rol' => 'Administrador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('usuarios')->insert([
                'nombre' => $faker->firstName,
                'email' => $faker->email,
                'contrasena' => bcrypt('secret'),
                'rol' => 'Usuario',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}
