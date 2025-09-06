<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\atributosDocente;

class GenerateFakeUsers extends Command
{
    protected $signature = 'generate:fake-users {count=10}';
    protected $description = 'Genera usuarios falsos con Faker';

    public function handle()
    {
        $faker = Faker::create();
        $count = $this->argument('count');

        for ($i = 0; $i < $count; $i++) {
            $isDocente = $faker->boolean(50);

            $user = User::create([
                'name' => $faker->firstName,
                'lastname1' => $faker->lastName,
                'lastname2' => $faker->lastName,
                'CI' => $faker->unique()->numerify('########'),
                'Celular' => '591' . $faker->numerify('########'),
                'fechadenac' => $faker->dateTimeBetween('-50 years', '-18 years'),
                'PaisReside' => $faker->country,
                'CiudadReside' => '', // ciudad vacía
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt(contraseña123),
                'email_verified_at' => now(),
            ]);

            if ($isDocente) {
                $atributosDocente = new atributosDocente();
                $atributosDocente->formacion = $faker->word;
                $atributosDocente->Especializacion = $faker->word;
                $atributosDocente->ExperienciaL = $faker->sentence;
                $atributosDocente->docente_id = $user->id;
                $atributosDocente->save();

                $user->assignRole('Docente');
            } else {
                $user->assignRole('Estudiante');
            }
        }

        $this->info("✅ $count usuarios falsos generados exitosamente.");
    }
}
