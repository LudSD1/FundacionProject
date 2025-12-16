<?php

namespace App\Console\Commands;

use App\Models\Cursos;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCodigoCurso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cursos:generate-codigo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera códigos únicos para cursos que no tienen codigoCurso';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generando códigos para cursos...');

        // Obtener cursos sin codigoCurso
        $cursosSinCodigo = Cursos::whereNull('codigoCurso')
            ->orWhere('codigoCurso', '')
            ->get();

        if ($cursosSinCodigo->isEmpty()) {
            $this->info('✅ Todos los cursos ya tienen código.');
            return Command::SUCCESS;
        }

        $this->info("Encontrados {$cursosSinCodigo->count()} cursos sin código.");

        $bar = $this->output->createProgressBar($cursosSinCodigo->count());
        $bar->start();

        foreach ($cursosSinCodigo as $curso) {
            // Generar slug desde el nombre del curso
            $slug = Str::slug($curso->nombreCurso);

            // Asegurar que sea único
            $originalSlug = $slug;
            $counter = 1;

            while (Cursos::where('codigoCurso', $slug)->where('id', '!=', $curso->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Actualizar el curso
            $curso->codigoCurso = $slug;
            $curso->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Códigos generados exitosamente!');

        return Command::SUCCESS;
    }
}
