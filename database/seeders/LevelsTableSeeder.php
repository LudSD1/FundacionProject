<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelsTableSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            [
                'level_number' => 1,
                'required_xp' => 0,
                'title' => 'Principiante',
                'description' => 'Comienza tu viaje de aprendizaje',
                'badge_image' => 'badges/level1.png'
            ],
            [
                'level_number' => 2,
                'required_xp' => 100,
                'title' => 'Estudiante',
                'description' => 'Estás dando tus primeros pasos',
                'badge_image' => 'badges/level2.png'
            ],
            [
                'level_number' => 3,
                'required_xp' => 300,
                'title' => 'Aprendiz',
                'description' => 'Continúa mejorando tus habilidades',
                'badge_image' => 'badges/level3.png'
            ],
            [
                'level_number' => 4,
                'required_xp' => 600,
                'title' => 'Explorador',
                'description' => 'Exploras nuevos horizontes',
                'badge_image' => 'badges/level4.png'
            ],
            [
                'level_number' => 5,
                'required_xp' => 1000,
                'title' => 'Aventurero',
                'description' => 'Te aventuras en el conocimiento',
                'badge_image' => 'badges/level5.png'
            ],
            [
                'level_number' => 6,
                'required_xp' => 1500,
                'title' => 'Erudito',
                'description' => 'Tu sabiduría crece cada día',
                'badge_image' => 'badges/level6.png'
            ],
            [
                'level_number' => 7,
                'required_xp' => 2100,
                'title' => 'Sabio',
                'description' => 'Tu conocimiento inspira a otros',
                'badge_image' => 'badges/level7.png'
            ],
            [
                'level_number' => 8,
                'required_xp' => 2800,
                'title' => 'Maestro',
                'description' => 'Has alcanzado la maestría',
                'badge_image' => 'badges/level8.png'
            ],
            [
                'level_number' => 9,
                'required_xp' => 3600,
                'title' => 'Experto',
                'description' => 'Tu experiencia es invaluable',
                'badge_image' => 'badges/level9.png'
            ],
            [
                'level_number' => 10,
                'required_xp' => 4500,
                'title' => 'Leyenda',
                'description' => 'Tu legado perdurará',
                'badge_image' => 'badges/level10.png'
            ],
        ];

        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['level_number' => $level['level_number']],
                $level
            );
        }
    }
}
