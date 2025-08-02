<?php

namespace Database\Seeders;

use App\Models\atributosDocente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class Administrador extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([

            'name' => 'Roxana',
            'lastname1' => 'Romay',
            'lastname2' => 'Araujo',
            'CI' => '00',
            'Celular' => '71234567',
            'fechadenac' => now(),
            'PaisReside' => 'Bolivia',
            'CiudadReside' => 'Cochabamba',
            'email' => 'educarparalavida.fund@gmail.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),

        ]);
        $atributosDocentes = new atributosDocente();

        $atributosDocentes->formacion = 'Admin';
        $atributosDocentes->Especializacion = 'Admin';
        $atributosDocentes->ExperienciaL = 'Admin';
        $atributosDocentes->docente_id = User::latest('id')->first()->id;
        $atributosDocentes->save();


        $user->assignRole('Administrador');


        $user2 = User::create([

            'name' => 'Juan',
            'lastname1' => 'Perez',
            'lastname2' => 'Perez',
            'CI' => '12345678',
            'Celular' => '59196584651',
            'fechadenac' => now(),
            'PaisReside' => 'Bolivia',
            'CiudadReside' => 'Cochabamba',
            'email' => 'speedhack422@gmail.com',
            'password' => bcrypt('JPP12345678'),
            'email_verified_at' => now(),
        ]);

        $atributosDocentes2 = new atributosDocente();

        $atributosDocentes2->formacion = 'Docente';
        $atributosDocentes2->Especializacion = 'Docente';
        $atributosDocentes2->ExperienciaL = 'Docente';
        $atributosDocentes2->docente_id = User::latest('id')->first()->id;
        $atributosDocentes2->save();


        $user2->assignRole('Docente');




        $user3 = User::create([

            'name' => 'Juan Carlos',
            'lastname1' => 'Bodoque',
            'lastname2' => 'Toledo',
            'CI' => '987654321',
            'Celular' => '56897461646',
            'fechadenac' => now(),
            'PaisReside' => 'Bolivia',
            'CiudadReside' => 'Cochabamba',
            'email' => 'ludtp350gt@gmail.com',
            'password' => bcrypt('LMM12926606'),
            'email_verified_at' => now(),

        ]);
        $user3->assignRole('Estudiante');


        $user4 = User::create([

            'name' => 'Ludwing',
            'lastname1' => 'Machicado',
            'lastname2' => 'Mullisaca',
            'CI' => '12926606',
            'Celular' => '59112926606',
            'fechadenac' => now(),
            'PaisReside' => 'Bolivia',
            'CiudadReside' => 'La Paz',
            'email' => 'ludtp350@gmail.com',
            'password' => bcrypt('LMM12926606'),
            'email_verified_at' => now(),

        ]);
        $atributosDocentes = new atributosDocente();

        $atributosDocentes->formacion = ' ';
        $atributosDocentes->Especializacion = ' ';
        $atributosDocentes->ExperienciaL = ' ';
        $atributosDocentes->docente_id = User::latest('id')->first()->id;
        $atributosDocentes->save();


        $user4->assignRole('Administrador');
    }
}
