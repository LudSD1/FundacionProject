<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expositores;
use Illuminate\Support\Facades\DB;

class ExpositoresSeeder extends Seeder
{
    public function run(): void
    {
        // Puedes usar el modelo directamente o DB::table('expositores')->insert([...])
        Expositores::insert([
            [
                'nombre' => 'Dra. Ana Torres',
                'especialidad' => 'Neurociencia',
                'empresa' => 'Instituto Neuromed',
                'biografia' => 'Investigadora con más de 10 años en neurociencia aplicada al aprendizaje.',
                'imagen' => 'ana_torres.jpg',
                'linkedin' => 'https://linkedin.com/in/anatorres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ing. Carlos López',
                'especialidad' => 'Inteligencia Artificial',
                'empresa' => 'TechAI Solutions',
                'biografia' => 'Desarrollador de soluciones IA en el sector salud y educación.',
                'imagen' => 'carlos_lopez.png',
                'linkedin' => 'https://linkedin.com/in/carloslopez',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lic. María Fernández',
                'especialidad' => 'Psicología Organizacional',
                'empresa' => 'HumanTalent',
                'biografia' => 'Consultora experta en desarrollo de equipos de alto rendimiento.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Dr. Luis Pérez',
                'especialidad' => 'Educación Digital',
                'empresa' => 'EduTech',
                'biografia' => 'Experto en metodologías de enseñanza online y blended learning.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dra. Sofía Martínez',
                'especialidad' => 'Gamificación',
                'empresa' => 'GameLearn',
                'biografia' => 'Investigadora en el uso de juegos para el aprendizaje efectivo.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ing. Javier Ramírez',
                'especialidad' => 'Desarrollo Web',
                'empresa' => 'WebSolutions',
                'biografia' => 'Desarrollador full-stack con enfoque en educación online.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lic. Patricia Gómez',
                'especialidad' => 'Marketing Digital',
                'empresa' => 'DigitalMarketingPro',
                'biografia' => 'Experta en estrategias de marketing para plataformas educativas.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dr. Andrés Torres',
                'especialidad' => 'Big Data',
                'empresa' => 'DataScience Corp',
                'biografia' => 'Analista de datos con experiencia en el sector educativo.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dra. Laura Sánchez',
                'especialidad' => 'Neuroeducación',
                'empresa' => 'NeuroEduca',
                'biografia' => 'Investigadora en la intersección de la neurociencia y la educación.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ing. Roberto Díaz',
                'especialidad' => 'Ciberseguridad',
                'empresa' => 'SecureTech',
                'biografia' => 'Experto en protección de datos y ciberseguridad en plataformas educativas.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lic. Elena Ruiz',
                'especialidad' => 'Diseño Instruccional',
                'empresa' => 'InstructDesign',
                'biografia' => 'Diseñadora instruccional con enfoque en experiencias de aprendizaje efectivas.',
                'imagen' => null,
                'linkedin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
