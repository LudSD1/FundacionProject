<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cursos;
use Carbon\Carbon;

class CursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cursos::insert([
            [
                'nombreCurso' => 'Curso de Fotografía Básica',
                'codigoCurso' => 'FOTO100',
                'descripcionC' => 'Aprende los fundamentos de la fotografía digital y técnicas de composición.',
                'fecha_ini' => Carbon::now()->subMonth(),
                'fecha_fin' => Carbon::now()->addMonth(),
                'archivoContenidodelCurso' => null,
                'notaAprobacion' => 60,
                'formato' => 'online',
                // 'estado' usa valor por defecto 'Activo'
                // 'tipo' usa valor por defecto 'curso'
                'docente_id' => 2,
                'edad_dirigida' => 'Adultos',
                'nivel' => 'Principiante',
                'precio' => 50.00,
                'imagen' => 'cursos/fotografia_basica.jpg',
                'duracion' => 15, // horas
                'cupos' => 25,
                'visibilidad' => 'Público',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombreCurso' => 'Taller de Escritura Creativa',
                'codigoCurso' => null,
                'descripcionC' => 'Desarrolla tu voz literaria y aprende técnicas de narrativa y poesía.',
                'fecha_ini' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addWeeks(8),
                'archivoContenidodelCurso' => 'docs/escritura_creativa.pdf',
                'notaAprobacion' => 70,
                'formato' => 'presencial',
                'docente_id' => 2,
                'edad_dirigida' => 'Adolescentes',
                'nivel' => 'Intermedio',
                'precio' => 100.00,
                'imagen' => null,
                'duracion' => 30,
                'cupos' => 20,
                'visibilidad' => 'Solo Registrados',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombreCurso' => 'Seminario de Gestión de Proyectos',
                'codigoCurso' => 'GP300',
                'descripcionC' => 'Mejora tus habilidades en planificación, ejecución y control de proyectos.',
                'fecha_ini' => Carbon::now()->addDays(7),
                'fecha_fin' => Carbon::now()->addDays(37),
                'archivoContenidodelCurso' => null,
                'notaAprobacion' => 75,
                'formato' => 'mixto',
                'docente_id' => 2,
                'edad_dirigida' => 'Adultos',
                'nivel' => 'Avanzado',
                'precio' => 200.00,
                'imagen' => 'cursos/gestion_proyectos.jpg',
                'duracion' => 40,
                'cupos' => 50,
                'visibilidad' => 'Privado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
