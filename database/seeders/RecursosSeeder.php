<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecursosSeeder extends Seeder
{
    public function run()
    {
        $recursos = [
            [
                'nombreRecurso' => 'Guía de Introducción',
                'descripcionRecursos' => 'Guía básica para comenzar con el curso',
                'tipoRecurso' => 'documento',
                'archivoRecurso' => 'recursos/guia-introduccion.pdf',
                'cursos_id' => 1,
                'progreso' => false
            ],
            [
                'nombreRecurso' => 'Video Tutorial - Primeros Pasos',
                'descripcionRecursos' => 'Video explicativo sobre cómo comenzar',
                'tipoRecurso' => 'video',
                'archivoRecurso' => 'https://www.youtube.com/watch?v=ejemplo',
                'cursos_id' => 1,
                'progreso' => false
            ],
            [
                'nombreRecurso' => 'Material de Lectura Complementario',
                'descripcionRecursos' => 'Lecturas adicionales para profundizar',
                'tipoRecurso' => 'documento',
                'archivoRecurso' => 'recursos/lecturas-complementarias.pdf',
                'cursos_id' => 1,
                'progreso' => false
            ],
        ];

        foreach ($recursos as $recurso) {
            DB::table('recursos')->updateOrInsert(
                [
                    'nombreRecurso' => $recurso['nombreRecurso'],
                    'cursos_id' => $recurso['cursos_id']
                ],
                array_merge($recurso, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
} 