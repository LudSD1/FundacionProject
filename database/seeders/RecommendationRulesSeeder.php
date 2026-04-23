<?php

namespace Database\Seeders;

use App\Models\RecommendationRule;
use Illuminate\Database\Seeder;

class RecommendationRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name'         => 'CATEGORY_AFFINITY',
                'display_name' => 'Afinidad por Categoría',
                'description'  => 'Recomendar cursos de categorías en las que el alumno ya se ha inscrito.',
                'weight'       => 30,
                'is_active'    => true,
                'config'       => json_encode(['min_enrollments' => 1]),
            ],
            [
                'name'         => 'LEVEL_PROGRESSION',
                'display_name' => 'Progresión de Nivel',
                'description'  => 'Si el alumno completó cursos básicos, recomendar intermedios/avanzados.',
                'weight'       => 20,
                'is_active'    => true,
                'config'       => json_encode([
                    'level_order' => ['Básico', 'Intermedio', 'Avanzado'],
                    'min_progress' => 70,
                ]),
            ],
            [
                'name'         => 'FORMAT_PREFERENCE',
                'display_name' => 'Preferencia de Formato',
                'description'  => 'Preferir el formato (Presencial/Virtual/Híbrido) más usado por el alumno.',
                'weight'       => 10,
                'is_active'    => true,
                'config'       => null,
            ],
            [
                'name'         => 'HIGH_RATING',
                'display_name' => 'Alta Calificación',
                'description'  => 'Priorizar cursos con alta calificación promedio (≥ umbral).',
                'weight'       => 15,
                'is_active'    => true,
                'config'       => json_encode(['min_rating' => 4.0, 'min_reviews' => 2]),
            ],
            [
                'name'         => 'POPULARITY',
                'display_name' => 'Popularidad',
                'description'  => 'Priorizar cursos con mayor cantidad de inscritos activos.',
                'weight'       => 10,
                'is_active'    => true,
                'config'       => null,
            ],
            [
                'name'         => 'COMPLETION_SIMILARITY',
                'display_name' => 'Similitud por Completados',
                'description'  => 'Recomendar cursos tomados por estudiantes con perfiles similares.',
                'weight'       => 15,
                'is_active'    => true,
                'config'       => json_encode(['min_common_courses' => 1, 'max_similar_users' => 50]),
            ],
        ];

        foreach ($rules as $rule) {
            RecommendationRule::updateOrCreate(
                ['name' => $rule['name']],
                $rule
            );
        }
    }
}
