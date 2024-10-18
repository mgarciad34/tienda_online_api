<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class cestasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 21; $i++) {
            DB::table('cestas')->insert([
                'usuario_id' => $i+1,
                'created_at' => now(),
                'updated_at' => now(),
                'total' => 0,
                'estado' => 'abierta',
            ]);
        }
    }
}
