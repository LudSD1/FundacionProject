<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Inscritos;
use App\Models\UserXP;
use App\Models\XPEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AchievementService
{
    protected $achievementTypes = [
        // Logros Académicos
        'QUIZ_MASTER' => [
            'title' => 'Maestro de Cuestionarios',
            'description' => 'Completa {value} cuestionarios con calificación perfecta',
            'values' => [1, 5, 10, 25, 50],
            'xp_rewards' => [100, 250, 500, 1000, 2000],
            'icons' => ['🎯', '🎯🎯', '🎯🎯🎯', '🎯🎯🎯🎯', '🎯🎯🎯🎯🎯'],
            'category' => 'academic'
        ],
        'FORUM_CONTRIBUTOR' => [
            'title' => 'Contribuidor del Foro',
            'description' => 'Participa en {value} discusiones del foro',
            'values' => [1, 10, 25, 50, 100],
            'xp_rewards' => [50, 150, 300, 600, 1200],
            'icons' => ['💭', '💭💭', '💭💭💭', '💭💭💭💭', '💭💭💭💭💭'],
            'category' => 'social'
        ],
        'RESOURCE_EXPLORER' => [
            'title' => 'Explorador de Recursos',
            'description' => 'Visualiza {value} recursos diferentes',
            'values' => [5, 15, 30, 50, 100],
            'xp_rewards' => [75, 200, 400, 800, 1500],
            'icons' => ['📚', '📚📚', '📚📚📚', '📚📚📚📚', '📚📚📚📚📚'],
            'category' => 'academic'
        ],
        'EARLY_BIRD' => [
            'title' => 'Madrugador',
            'description' => 'Completa {value} actividades antes de tiempo',
            'values' => [1, 5, 10, 25, 50],
            'xp_rewards' => [100, 250, 500, 1000, 2000],
            'icons' => ['🌅', '🌅🌅', '🌅🌅🌅', '🌅🌅🌅🌅', '🌅🌅🌅🌅🌅'],
            'category' => 'engagement'
        ],
        'STREAK_MASTER' => [
            'title' => 'Maestro de la Constancia',
            'description' => 'Mantén una racha de actividad de {value} días',
            'values' => [3, 7, 14, 30, 60],
            'xp_rewards' => [150, 300, 600, 1200, 2400],
            'icons' => ['🔥', '🔥🔥', '🔥🔥🔥', '🔥🔥🔥🔥', '🔥🔥🔥🔥🔥'],
            'category' => 'engagement'
        ],
        // Logros de Congresos
        'CONGRESS_ENROLL' => [
            'title' => 'Participante de Congreso',
            'description' => 'Te has inscrito al congreso "{value}"',
            'values' => [1],
            'xp_rewards' => [200],
            'icons' => ['🎓'],
            'category' => 'events'
        ],
        'CONGRESS_PARTICIPANT' => [
            'title' => 'Asistente de Congresos',
            'description' => 'Inscríbete a {value} congresos',
            'values' => [1, 3, 5, 10],
            'xp_rewards' => [200, 600, 1000, 2000],
            'icons' => ['🎓', '🎓🎓', '🎓🎓🎓', '🏅'],
            'category' => 'events'
        ],
        // Logros de Cursos
        'COURSE_ENROLL' => [
            'title' => 'Aprendiz Curioso',
            'description' => 'Te has inscrito al curso "{value}"',
            'values' => [1],
            'xp_rewards' => [100],
            'icons' => ['📘'],
            'category' => 'courses'
        ],
        'COURSE_COLLECTOR' => [
            'title' => 'Coleccionista de Cursos',
            'description' => 'Inscríbete a {value} cursos distintos',
            'values' => [1, 3, 5, 10, 20],
            'xp_rewards' => [100, 300, 600, 1200, 2000],
            'icons' => ['📘', '📗📘', '📕📗📘', '📚', '🏅'],
            'category' => 'courses'
        ],
        'COURSE_FINISHER' => [
            'title' => 'Finisher Académico',
            'description' => 'Completa {value} curso(s)',
            'values' => [1, 3, 5, 10],
            'xp_rewards' => [200, 600, 1200, 2500],
            'icons' => ['✅', '✅✅', '✅✅✅', '🎖️'],
            'category' => 'courses'
        ],
        'MODULE_MASTER' => [
            'title' => 'Dominador de Temas',
            'description' => 'Has completado {value} módulos de curso',
            'values' => [5, 10, 20, 40],
            'xp_rewards' => [150, 400, 800, 1600],
            'icons' => ['📄', '📄📄', '📄📄📄', '📂'],
            'category' => 'courses'
        ],
        'CERTIFICATE_EARNED' => [
            'title' => 'Coleccionista de Certificados',
            'description' => 'Obtén {value} certificado(s)',
            'values' => [1, 3, 5, 10],
            'xp_rewards' => [200, 500, 1000, 2000],
            'icons' => ['📜', '📜📜', '📜📜📜', '🏆'],
            'category' => 'academic'
        ],
        // Logros Secretos
        'NIGHT_OWL' => [
            'title' => 'Explorador Nocturno',
            'description' => 'Completa {value} actividad(es) entre las 12 AM y las 4 AM',
            'values' => [1],
            'xp_rewards' => [500],
            'icons' => ['🌙'],
            'category' => 'special'
        ],
        'SPEED_RUNNER' => [
            'title' => 'Velocista',
            'description' => 'Completa {value} cuestionario(s) en menos de la mitad del tiempo con calificación perfecta',
            'values' => [1],
            'xp_rewards' => [1000],
            'icons' => ['⚡'],
            'category' => 'special'
        ],
        'DAILY_ACTIVITIES' => [
            'title' => 'Maratón de Estudio',
            'description' => 'Completa {value} actividades diferentes en un solo día',
            'values' => [5],
            'xp_rewards' => [2000],
            'icons' => ['🏃'],
            'category' => 'special'
        ],
    ];

    public function checkAndAwardAchievements(Inscritos $inscrito, string $type, $currentValue)
    {
        // 1. Verificar logros hardcodeados del array
        if (isset($this->achievementTypes[$type])) {
            $typeConfig = $this->achievementTypes[$type];
            foreach ($typeConfig['values'] as $index => $requiredValue) {
                if ($currentValue >= $requiredValue) {
                    $achievement = $this->findOrCreateAchievement($type, $index);
                    $achievement->award($inscrito);
                }
            }
        }

        // 2. Verificar logros creados desde el CRUD (base de datos)
        //    Busca logros del mismo tipo que NO estén en el array hardcodeado
        $dbAchievements = Achievement::where('type', $type)
            ->whereNull('deleted_at')
            ->get();

        foreach ($dbAchievements as $achievement) {
            if ($currentValue >= $achievement->requirement_value) {
                $achievement->award($inscrito);
            }
        }
    }

    protected function findOrCreateAchievement($type, $index)
    {
        $typeConfig = $this->achievementTypes[$type];
        $slug = strtolower($type) . '_' . $typeConfig['values'][$index];

        return Achievement::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $typeConfig['title'] . ' ' . $typeConfig['icons'][$index],
                'description' => str_replace('{value}', $typeConfig['values'][$index], $typeConfig['description']),
                'icon' => $typeConfig['icons'][$index],
                'xp_reward' => $typeConfig['xp_rewards'][$index],
                'type' => $type,
                'category' => $typeConfig['category'] ?? 'general',
                'requirement_value' => $typeConfig['values'][$index],
                'is_secret' => in_array($typeConfig['category'] ?? '', ['special'])
            ]
        );
    }

    public function getProgress(Inscritos $inscrito, string $type)
    {
        if (!isset($this->achievementTypes[$type])) {
            return null;
        }

        $cacheKey = "achievement_progress:{$inscrito->id}:{$type}";
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($inscrito, $type) {
            $typeConfig = $this->achievementTypes[$type];
            $earnedAchievements = Achievement::whereType($type)
                ->whereHas('inscritos', function ($query) use ($inscrito) {
                    $query->where('inscrito_id', $inscrito->id);
                })
                ->count();

            $nextValue = $typeConfig['values'][$earnedAchievements] ?? null;
            
            return [
                'type' => $type,
                'earned' => $earnedAchievements,
                'next_value' => $nextValue,
                'total_possible' => count($typeConfig['values'])
            ];
        });
    }

    public function createSecretAchievement($title, $description, $icon, $xpReward)
    {
        return Achievement::create([
            'title' => $title,
            'description' => $description,
            'slug' => Str::slug($title),
            'icon' => $icon,
            'xp_reward' => $xpReward,
            'is_secret' => true
        ]);
    }

    /**
     * Calcula la racha de días consecutivos con actividad para un inscrito.
     */
    public function calculateStreak(Inscritos $inscrito): int
    {
        $activityDates = XPEvent::where('users_id', $inscrito->estudiante_id)
            ->select(DB::raw('DATE(created_at) as fecha'))
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->pluck('fecha')
            ->toArray();

        if (empty($activityDates)) {
            return 0;
        }

        $streak = 1;
        for ($i = 0; $i < count($activityDates) - 1; $i++) {
            $current = \Carbon\Carbon::parse($activityDates[$i]);
            $previous = \Carbon\Carbon::parse($activityDates[$i + 1]);

            if ($current->diffInDays($previous) === 1) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
} 