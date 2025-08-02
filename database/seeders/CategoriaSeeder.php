<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            [
                'name' => 'Programación',
                'description' => 'Cursos de programación y desarrollo de software',
            ],
            [
                'name' => 'Diseño',
                'description' => 'Cursos de diseño gráfico y digital',
            ],
            [
                'name' => 'Marketing Digital',
                'description' => 'Cursos de marketing y estrategias digitales',
            ],
            [
                'name' => 'Idiomas',
                'description' => 'Cursos de diferentes idiomas',
            ],
            [
                'name' => 'Desarrollo Personal',
                'description' => 'Cursos de crecimiento y desarrollo personal',
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categoria')->updateOrInsert(
                ['slug' => Str::slug($categoria['name'])],
                [
                    'name' => $categoria['name'],
                    'description' => $categoria['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
} 