<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;
use App\Models\Cursos;
use App\Models\User;
use Carbon\Carbon;

class GenerateFakeCursos extends Command
{
    protected $signature = 'generate:fake-cursos {count=5} {--type=curso} {--docente_id=}';
    protected $description = 'Genera cursos o congresos falsos con Faker';

    public function handle()
    {
        $faker = Faker::create();
        $count = $this->argument('count');
        $tipo = $this->option('type'); // curso o congreso
        $docenteId = $this->option('docente_id');

        // Validar tipo
        if (!in_array($tipo, ['curso', 'congreso'])) {
            $this->error('El tipo debe ser "curso" o "congreso"');
            return 1;
        }

        // Si se especificó un docente_id, verificar que exista
        if ($docenteId) {
            $docente = User::find($docenteId);
            if (!$docente) {
                $this->error('El docente especificado no existe');
                return 1;
            }
        }

        $this->info("Generando $count {$tipo}s falsos...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            // Fechas: fecha_ini entre hoy y 30 días en el futuro
            $fechaInicio = Carbon::now()->addDays($faker->numberBetween(0, 30));
            // fecha_fin entre 1 y 90 días después de fecha_ini
            $fechaFin = (clone $fechaInicio)->addDays($faker->numberBetween(1, 90));

            // Si no se especificó un docente_id, seleccionar uno aleatorio
            if (!$docenteId) {
                $docentes = User::role('Docente')->inRandomOrder()->first();
                $docenteId = $docentes ? $docentes->id : null;
            }

            // Generar nombre según el tipo
            if ($tipo == 'curso') {
                $nombre = "Curso de " . $faker->randomElement([
                    'Programación', 'Diseño Gráfico', 'Marketing Digital',
                    'Desarrollo Web', 'Inteligencia Artificial', 'Análisis de Datos',
                    'Redes Sociales', 'Fotografía', 'Edición de Video', 'Liderazgo'
                ]);
            } else {
                $nombre = "Congreso de " . $faker->randomElement([
                    'Innovación', 'Tecnología', 'Educación', 'Salud',
                    'Ciencias', 'Humanidades', 'Artes', 'Negocios',
                    'Emprendimiento', 'Desarrollo Sostenible'
                ]);
            }

            $curso = Cursos::create([
                'nombreCurso' => $nombre,
                'codigoCurso' => strtoupper($faker->bothify('??###')),
                'descripcionC' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                'fecha_ini' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'notaAprobacion' => $faker->numberBetween(60, 80),
                'formato' => $faker->randomElement(['Presencial', 'Virtual', 'Híbrido']),
                'estado' => true,
                'tipo' => $tipo,
                'docente_id' => $docenteId,
                'nivel' => $faker->randomElement(['Básico', 'Intermedio', 'Avanzado']),
                'edad_dirigida' => $faker->randomElement(['Niños', 'Adolescentes', 'Adultos', 'Todas las edades']),
                'precio' => $faker->randomFloat(2, 0, 1000),
                'duracion' => $faker->numberBetween(3600, 36000), // Entre 1 y 10 horas en segundos
                'cupos' => $faker->numberBetween(10, 100),
                'visibilidad' => true,
                'certificados_activados' => $faker->boolean(80),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ $count {$tipo}s falsos generados exitosamente.");
    }
}
