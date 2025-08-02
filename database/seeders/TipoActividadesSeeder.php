<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoActividadesSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['nombre' => 'Tarea', 'slug' => Str::slug('Tarea'), 'descripcion' => 'Actividad de entrega de ejercicios o trabajos.'],
            ['nombre' => 'Cuestionario', 'slug' => Str::slug('Cuestionario'), 'descripcion' => 'Actividad con preguntas de opción múltiple, verdadero/falso, etc.'],
            ['nombre' => 'Foro', 'slug' => Str::slug('Foro'), 'descripcion' => 'Espacio para discusión o participación abierta entre estudiantes.'],
            ['nombre' => 'Evaluación', 'slug' => Str::slug('Evaluación'), 'descripcion' => 'Actividad de evaluación sumativa o formativa.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_actividades')->updateOrInsert(
                ['slug' => $tipo['slug']],
                $tipo
            );
        }
    }
}