<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class EstudiantesYDocentesSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 estudiantes
        for ($i = 1; $i <= 10; $i++) {
            $estudiante = User::create([
                'name' => 'Estudiante'.$i,
                'lastname1' => 'ApellidoP'.$i,
                'lastname2' => 'ApellidoM'.$i,
                'CI' => str_pad($i, 8, '1', STR_PAD_LEFT),
                'Celular' => '700000'.str_pad($i, 2, '0', STR_PAD_LEFT),
                'fechadenac' => now()->subYears(rand(18, 25)),
                'PaisReside' => 'Bolivia',
                'CiudadReside' => 'Ciudad'.$i,
                'email' => 'estudiante'.$i.'@demo.com',
                'password' => bcrypt('estudiante'.$i.'123'),
            ]);
            $estudiante->assignRole('Estudiante');
        }

        // Crear 5 docentes
        for ($i = 1; $i <= 5; $i++) {
            $docente = User::create([
                'name' => 'Docente'.$i,
                'lastname1' => 'ApellidoP'.$i,
                'lastname2' => 'ApellidoM'.$i,
                'CI' => str_pad($i, 8, '2', STR_PAD_LEFT),
                'Celular' => '710000'.str_pad($i, 2, '0', STR_PAD_LEFT),
                'fechadenac' => now()->subYears(rand(28, 50)),
                'PaisReside' => 'Bolivia',
                'CiudadReside' => 'CiudadD'.$i,
                'email' => 'docente'.$i.'@demo.com',
                'password' => bcrypt('docente'.$i.'123'),
            ]);
            $docente->assignRole('Docente');
        }
    }
}
