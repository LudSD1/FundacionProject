<?php

namespace App\Services;

use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\RecommendationLog;
use App\Models\RecommendationRule;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Punto de entrada principal: genera recomendaciones para un usuario.
     */
    public function getRecommendations(User $user, int $limit = 6): Collection
    {
        $cacheKey = "recommendations:user:{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($user, $limit) {
            $profile    = $this->buildUserProfile($user);
            $candidates = $this->getCandidateCourses($user);
            $rules      = RecommendationRule::active()->get();

            if ($candidates->isEmpty() || $rules->isEmpty()) {
                return collect();
            }

            // Calcular el peso total para normalizar
            $totalWeight = $rules->sum('weight');
            if ($totalWeight === 0) $totalWeight = 1;

            // Scoring de cada curso candidato
            $scored = $candidates->map(function (Cursos $curso) use ($profile, $rules, $totalWeight) {
                $scoreDetails = [];
                $totalScore   = 0;

                foreach ($rules as $rule) {
                    $ruleScore = $this->applyRule($rule->name, $curso, $profile, $rule);
                    $weighted  = ($ruleScore * $rule->weight) / $totalWeight;
                    $totalScore += $weighted;

                    $scoreDetails[$rule->name] = [
                        'raw'      => round($ruleScore, 4),
                        'weighted' => round($weighted, 4),
                        'weight'   => $rule->weight,
                    ];
                }

                $curso->recommendation_score   = round($totalScore * 100, 2);
                $curso->recommendation_details = $scoreDetails;
                $curso->recommendation_reason  = $this->buildReason($scoreDetails, $profile);

                return $curso;
            });

            // Ordenar por score descendente y tomar top N
            $top = $scored->sortByDesc('recommendation_score')->take($limit)->values();

            // Registrar en logs (fire-and-forget)
            foreach ($top as $curso) {
                $this->logRecommendation($user->id, $curso->id, $curso->recommendation_score, $curso->recommendation_details);
            }

            return $top;
        });
    }

    /**
     * Construye el perfil de preferencias del alumno.
     */
    public function buildUserProfile(User $user): array
    {
        $inscripciones = Inscritos::where('estudiante_id', $user->id)
            ->whereNull('deleted_at')
            ->with(['cursos' => fn($q) => $q->with('categorias')])
            ->get();

        // Categorías frecuentes
        $categoryFrequency = [];
        $formats    = [];
        $levels     = [];
        $progresses = [];
        $cursoIds   = [];

        foreach ($inscripciones as $inscrito) {
            $curso = $inscrito->cursos;
            if (!$curso) continue;

            $cursoIds[] = $curso->id;
            $formats[]  = $curso->formato;
            $levels[]   = $curso->nivel;
            $progresses[] = $inscrito->progreso ?? 0;

            foreach ($curso->categorias as $cat) {
                $categoryFrequency[$cat->id] = ($categoryFrequency[$cat->id] ?? 0) + 1;
            }
        }

        // Formato más frecuente
        $formatCounts   = array_count_values(array_filter($formats));
        $preferredFormat = !empty($formatCounts) ? array_search(max($formatCounts), $formatCounts) : null;

        // Nivel estimado: el nivel más alto con progreso ≥ 70%
        $completedLevels = [];
        foreach ($inscripciones as $inscrito) {
            if (($inscrito->progreso ?? 0) >= 70 && $inscrito->cursos) {
                $completedLevels[] = $inscrito->cursos->nivel;
            }
        }

        $levelOrder = ['Básico' => 1, 'Intermedio' => 2, 'Avanzado' => 3];
        $maxCompletedLevel = 0;
        foreach ($completedLevels as $lvl) {
            $maxCompletedLevel = max($maxCompletedLevel, $levelOrder[$lvl] ?? 0);
        }
        // Recomendar el siguiente nivel
        $suggestedLevelIndex = min($maxCompletedLevel + 1, 3);
        $suggestedLevel = array_search($suggestedLevelIndex, $levelOrder) ?: 'Básico';

        // Promedio de progreso
        $avgProgress = count($progresses) > 0 ? array_sum($progresses) / count($progresses) : 0;

        // Categorías favoritas top (IDs ordenados por frecuencia)
        arsort($categoryFrequency);
        $topCategoryIds = array_keys(array_slice($categoryFrequency, 0, 5, true));

        return [
            'user_id'            => $user->id,
            'enrolled_curso_ids' => $cursoIds,
            'category_frequency' => $categoryFrequency,
            'top_category_ids'   => $topCategoryIds,
            'preferred_format'   => $preferredFormat,
            'suggested_level'    => $suggestedLevel,
            'max_completed_level'=> $maxCompletedLevel,
            'avg_progress'       => round($avgProgress, 2),
            'total_enrollments'  => count($cursoIds),
        ];
    }

    /**
     * Obtiene cursos candidatos: activos, públicos, y donde el alumno NO está inscrito.
     */
    private function getCandidateCourses(User $user): Collection
    {
        $enrolledCursoIds = Inscritos::where('estudiante_id', $user->id)
            ->whereNull('deleted_at')
            ->pluck('cursos_id')
            ->toArray();

        return Cursos::whereNull('deleted_at')
            ->where('fecha_fin', '>=', now())
            ->where('visibilidad', 'Público')
            ->whereNotIn('id', $enrolledCursoIds)
            ->with(['categorias', 'calificaciones', 'inscritos'])
            ->withAvg('calificaciones', 'puntuacion')
            ->withCount(['calificaciones', 'inscritos'])
            ->get();
    }

    /**
     * Ejecuta una regla individual y devuelve score entre 0.0 y 1.0
     */
    private function applyRule(string $ruleName, Cursos $curso, array $profile, RecommendationRule $rule): float
    {
        return match ($ruleName) {
            'CATEGORY_AFFINITY'     => $this->scoreByCategory($curso, $profile),
            'LEVEL_PROGRESSION'     => $this->scoreByLevel($curso, $profile, $rule),
            'FORMAT_PREFERENCE'     => $this->scoreByFormat($curso, $profile),
            'HIGH_RATING'           => $this->scoreByRating($curso, $rule),
            'POPULARITY'            => $this->scoreByPopularity($curso),
            'COMPLETION_SIMILARITY' => $this->scoreBySimilarity($curso, $profile, $rule),
            default                 => 0.0,
        };
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 1: Afinidad por Categoría
    // ──────────────────────────────────────────────────────────────
    private function scoreByCategory(Cursos $curso, array $profile): float
    {
        if (empty($profile['top_category_ids'])) {
            return 0.0;
        }

        $cursoCategoryIds = $curso->categorias->pluck('id')->toArray();
        if (empty($cursoCategoryIds)) {
            return 0.0;
        }

        $matches = array_intersect($cursoCategoryIds, $profile['top_category_ids']);

        if (empty($matches)) {
            return 0.0;
        }

        // Cuanto más alta la frecuencia de la categoría, mayor el score
        $maxFreq   = max($profile['category_frequency']) ?: 1;
        $bestMatch = 0;
        foreach ($matches as $catId) {
            $freq = $profile['category_frequency'][$catId] ?? 0;
            $bestMatch = max($bestMatch, $freq / $maxFreq);
        }

        return $bestMatch;
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 2: Progresión de Nivel
    // ──────────────────────────────────────────────────────────────
    private function scoreByLevel(Cursos $curso, array $profile, RecommendationRule $rule): float
    {
        $levelOrder = ['Básico' => 1, 'Intermedio' => 2, 'Avanzado' => 3];
        $cursoLevelIndex   = $levelOrder[$curso->nivel] ?? 0;
        $suggestedIndex    = $levelOrder[$profile['suggested_level']] ?? 1;

        if ($cursoLevelIndex === 0) {
            return 0.3; // Sin nivel definido: puntuación neutral
        }

        // Coincidencia perfecta con el nivel sugerido
        if ($cursoLevelIndex === $suggestedIndex) {
            return 1.0;
        }

        // Un nivel arriba o abajo: puntuación parcial
        $diff = abs($cursoLevelIndex - $suggestedIndex);
        return max(0.0, 1.0 - ($diff * 0.4));
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 3: Preferencia de Formato
    // ──────────────────────────────────────────────────────────────
    private function scoreByFormat(Cursos $curso, array $profile): float
    {
        if (!$profile['preferred_format'] || !$curso->formato) {
            return 0.3; // Sin datos: neutral
        }

        return strtolower($curso->formato) === strtolower($profile['preferred_format']) ? 1.0 : 0.2;
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 4: Alta Calificación
    // ──────────────────────────────────────────────────────────────
    private function scoreByRating(Cursos $curso, RecommendationRule $rule): float
    {
        $avgRating  = $curso->calificaciones_avg_puntuacion ?? 0;
        $minRating  = $rule->getConfigValue('min_rating', 4.0);
        $minReviews = $rule->getConfigValue('min_reviews', 2);

        if ($curso->calificaciones_count < $minReviews) {
            return 0.3; // Pocos reviews: neutral
        }

        if ($avgRating >= 5.0) return 1.0;
        if ($avgRating >= $minRating) return 0.6 + (($avgRating - $minRating) / (5.0 - $minRating)) * 0.4;

        return max(0.0, $avgRating / 5.0 * 0.5);
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 5: Popularidad
    // ──────────────────────────────────────────────────────────────
    private function scoreByPopularity(Cursos $curso): float
    {
        $count = $curso->inscritos_count ?? 0;

        if ($count === 0) return 0.1;
        if ($count >= 50)  return 1.0;
        if ($count >= 20)  return 0.8;
        if ($count >= 10)  return 0.6;
        if ($count >= 5)   return 0.4;

        return 0.2;
    }

    // ──────────────────────────────────────────────────────────────
    //  REGLA 6: Similitud Colaborativa (Collaborative Filtering básico)
    // ──────────────────────────────────────────────────────────────
    private function scoreBySimilarity(Cursos $curso, array $profile, RecommendationRule $rule): float
    {
        if (empty($profile['enrolled_curso_ids'])) {
            return 0.0;
        }

        $minCommon = $rule->getConfigValue('min_common_courses', 1);
        $maxUsers  = $rule->getConfigValue('max_similar_users', 50);

        // Encontrar estudiantes que comparten al menos N cursos con el usuario
        $similarUserIds = DB::table('inscritos')
            ->whereIn('cursos_id', $profile['enrolled_curso_ids'])
            ->where('estudiante_id', '!=', $profile['user_id'])
            ->whereNull('deleted_at')
            ->groupBy('estudiante_id')
            ->havingRaw('COUNT(DISTINCT cursos_id) >= ?', [$minCommon])
            ->limit($maxUsers)
            ->pluck('estudiante_id');

        if ($similarUserIds->isEmpty()) {
            return 0.0;
        }

        // Contar cuántos de estos usuarios similares están inscritos en el curso candidato
        $similarEnrolled = DB::table('inscritos')
            ->where('cursos_id', $curso->id)
            ->whereIn('estudiante_id', $similarUserIds)
            ->whereNull('deleted_at')
            ->count();

        if ($similarEnrolled === 0) {
            return 0.0;
        }

        // Normalizar: proporción de usuarios similares que tomaron este curso
        return min(1.0, $similarEnrolled / max(1, $similarUserIds->count()));
    }

    // ──────────────────────────────────────────────────────────────
    //  Generar la razón legible de la recomendación
    // ──────────────────────────────────────────────────────────────
    private function buildReason(array $scoreDetails, array $profile): string
    {
        // Encontrar la regla con mayor score ponderado
        $topRule = null;
        $topWeighted = 0;

        foreach ($scoreDetails as $ruleName => $detail) {
            if ($detail['weighted'] > $topWeighted) {
                $topWeighted = $detail['weighted'];
                $topRule     = $ruleName;
            }
        }

        return match ($topRule) {
            'CATEGORY_AFFINITY'     => 'Basado en tus categorías de interés favoritas',
            'LEVEL_PROGRESSION'     => 'Recomendado para tu próximo nivel: ' . ($profile['suggested_level'] ?? ''),
            'FORMAT_PREFERENCE'     => 'Coincide con tu formato preferido: ' . ($profile['preferred_format'] ?? ''),
            'HIGH_RATING'           => 'Curso altamente valorado por otros estudiantes',
            'POPULARITY'            => 'Curso popular entre la comunidad',
            'COMPLETION_SIMILARITY' => 'Estudiantes con perfil similar tomaron este curso',
            default                 => 'Recomendado para ti',
        };
    }

    /**
     * Registrar una recomendación mostrada.
     */
    private function logRecommendation(int $userId, int $cursoId, float $score, array $rulesApplied): void
    {
        RecommendationLog::create([
            'user_id'       => $userId,
            'curso_id'      => $cursoId,
            'score'         => $score,
            'rules_applied' => $rulesApplied,
        ]);
    }

    /**
     * Registrar click en una recomendación.
     */
    public function trackClick(int $userId, int $cursoId): void
    {
        RecommendationLog::where('user_id', $userId)
            ->where('curso_id', $cursoId)
            ->whereNull('clicked_at')
            ->latest()
            ->first()
            ?->update([
                'clicked'    => true,
                'clicked_at' => now(),
            ]);
    }

    /**
     * Invalidar el caché de recomendaciones de un usuario.
     */
    public function invalidateCache(int $userId): void
    {
        Cache::forget("recommendations:user:{$userId}");
    }
}
