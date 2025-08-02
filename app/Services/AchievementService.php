<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Inscritos;
use App\Models\UserXP;
use Illuminate\Support\Facades\Cache;

class AchievementService
{
    protected $achievementTypes = [
        'QUIZ_MASTER' => [
            'title' => 'Maestro de Cuestionarios',
            'description' => 'Completa {value} cuestionarios con calificación perfecta',
            'values' => [1, 5, 10, 25, 50],
            'xp_rewards' => [100, 250, 500, 1000, 2000],
            'icons' => ['🎯', '🎯🎯', '🎯🎯🎯', '🎯🎯🎯🎯', '🎯🎯🎯🎯🎯']
        ],
        'FORUM_CONTRIBUTOR' => [
            'title' => 'Contribuidor del Foro',
            'description' => 'Participa en {value} discusiones del foro',
            'values' => [1, 10, 25, 50, 100],
            'xp_rewards' => [50, 150, 300, 600, 1200],
            'icons' => ['💭', '💭💭', '💭💭💭', '💭💭💭💭', '💭💭💭💭💭']
        ],
        'RESOURCE_EXPLORER' => [
            'title' => 'Explorador de Recursos',
            'description' => 'Visualiza {value} recursos diferentes',
            'values' => [5, 15, 30, 50, 100],
            'xp_rewards' => [75, 200, 400, 800, 1500],
            'icons' => ['📚', '📚📚', '📚📚📚', '📚📚📚📚', '📚📚📚📚📚']
        ],
        'EARLY_BIRD' => [
            'title' => 'Madrugador',
            'description' => 'Completa {value} actividades antes de tiempo',
            'values' => [1, 5, 10, 25, 50],
            'xp_rewards' => [100, 250, 500, 1000, 2000],
            'icons' => ['🌅', '🌅🌅', '🌅🌅🌅', '🌅🌅🌅🌅', '🌅🌅🌅🌅🌅']
        ],
        'STREAK_MASTER' => [
            'title' => 'Maestro de la Constancia',
            'description' => 'Mantén una racha de actividad de {value} días',
            'values' => [3, 7, 14, 30, 60],
            'xp_rewards' => [150, 300, 600, 1200, 2400],
            'icons' => ['🔥', '🔥🔥', '🔥🔥🔥', '🔥🔥🔥🔥', '🔥🔥🔥🔥🔥']
        ]
    ];

    public function checkAndAwardAchievements(Inscritos $inscrito, string $type, $currentValue)
    {
        if (!isset($this->achievementTypes[$type])) {
            return;
        }

        $typeConfig = $this->achievementTypes[$type];
        
        foreach ($typeConfig['values'] as $index => $requiredValue) {
            if ($currentValue >= $requiredValue) {
                $achievement = $this->findOrCreateAchievement($type, $index);
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
                'requirement_value' => $typeConfig['values'][$index],
                'secret' => false
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
            'slug' => \Str::slug($title),
            'icon' => $icon,
            'xp_reward' => $xpReward,
            'secret' => true
        ]);
    }
} 