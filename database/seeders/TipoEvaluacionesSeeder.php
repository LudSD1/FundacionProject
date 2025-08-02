<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class TipoEvaluacionesSeeder extends Seeder
{
    public function run(): void
    {
        $tiposEvaluaciones = [
            ['nombre' => 'Cuestionario', 'slug' => Str::slug('Cuestionario')],
            ['nombre' => 'Entrega de archivo', 'slug' => Str::slug('Entrega de archivo')],
            ['nombre' => 'Evaluación oral', 'slug' => Str::slug('Evaluación oral')],
            ['nombre' => 'Examen práctico', 'slug' => Str::slug('Examen práctico')],
        ];

        foreach ($tiposEvaluaciones as $tipo) {
            DB::table('tipo_evaluaciones')->updateOrInsert(
                ['slug' => $tipo['slug']], // Evita duplicados basados en el slug
                $tipo
            );
        }
    }
}
