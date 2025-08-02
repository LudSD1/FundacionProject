<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XpEventTypesSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['nombre' => 'Completar tarea', 'slug' => 'completar_tarea', 'descripcion' => 'El estudiante completó una tarea.', 'xp_base' => 50],
            ['nombre' => 'Participar en foro', 'slug' => 'participar_foro', 'descripcion' => 'El estudiante participó en un foro.', 'xp_base' => 20],
            ['nombre' => 'Realizar evaluación', 'slug' => 'realizar_evaluacion', 'descripcion' => 'El estudiante completó una evaluación.', 'xp_base' => 100],
            ['nombre' => 'Responder cuestionario', 'slug' => 'responder_cuestionario', 'descripcion' => 'El estudiante respondió un cuestionario.', 'xp_base' => 70],
            ['nombre' => 'Acceso diario', 'slug' => 'acceso_diario', 'descripcion' => 'El estudiante accedió a la plataforma hoy.', 'xp_base' => 10],
        ];

        foreach ($tipos as $tipo) {
            DB::table('xp_event_types')->updateOrInsert(
                ['slug' => $tipo['slug']],
                $tipo
            );
        }
    }
}