<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AchievementsTableSeeder extends Seeder
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
        ]
    ];

    protected $secretAchievements = [
        [
            'title' => 'Explorador Nocturno',
            'description' => 'Completa una actividad entre las 12 AM y las 4 AM',
            'icon' => '🌙',
            'xp_reward' => 500,
            'type' => 'NIGHT_OWL',
            'requirement_value' => 1,
            'category' => 'special'
        ],
        [
            'title' => 'Velocista',
            'description' => 'Completa un cuestionario en menos de 1 minuto con calificación perfecta',
            'icon' => '⚡',
            'xp_reward' => 1000,
            'type' => 'SPEED_RUNNER',
            'requirement_value' => 1,
            'category' => 'special'
        ],
        [
            'title' => 'Sabio del Foro',
            'description' => 'Ayuda a otros respondiendo 50 mensajes en el foro',
            'icon' => '👑',
            'xp_reward' => 1500,
            'type' => 'FORUM_HELPER',
            'requirement_value' => 50,
            'category' => 'social'
        ],
        [
            'title' => 'Maratón de Estudio',
            'description' => 'Completa 5 actividades diferentes en un solo día',
            'icon' => '🏃',
            'xp_reward' => 2000,
            'type' => 'DAILY_ACTIVITIES',
            'requirement_value' => 5,
            'category' => 'special'
        ]
    ];

    public function run()
    {
        $this->createRegularAchievements();
        $this->createSecretAchievements();
    }

    protected function createRegularAchievements()
    {
        foreach ($this->achievementTypes as $type => $config) {
            $this->validateAchievementConfig($config);

            foreach ($config['values'] as $index => $value) {
                $this->createAchievement([
                    'type' => $type,
                    'title' => $config['title'] . ' ' . $config['icons'][$index],
                    'description' => str_replace('{value}', $value, $config['description']),
                    'icon' => $config['icons'][$index],
                    'xp_reward' => $config['xp_rewards'][$index],
                    'requirement_value' => $value,
                    'category' => $config['category'],
                    'is_secret' => false
                ]);
            }
        }
    }

    protected function createSecretAchievements()
    {
        foreach ($this->secretAchievements as $achievement) {
            $this->createAchievement(array_merge($achievement, ['is_secret' => true]));
        }
    }

    protected function createAchievement(array $data)
    {
        $slug = isset($data['slug']) ? $data['slug'] :
                strtolower($data['type']) . '_' . $data['requirement_value'];

        Achievement::updateOrCreate(
            ['slug' => $slug],
            $data
        );
    }

    protected function validateAchievementConfig(array $config)
    {
        $requiredFields = ['title', 'description', 'values', 'xp_rewards', 'icons', 'category'];

        foreach ($requiredFields as $field) {
            if (!isset($config[$field])) {
                throw new \InvalidArgumentException("El campo {$field} es requerido en la configuración del logro");
            }
        }

        if (count($config['values']) !== count($config['xp_rewards']) ||
            count($config['values']) !== count($config['icons'])) {
            throw new \InvalidArgumentException("Los arrays values, xp_rewards e icons deben tener la misma longitud");
        }
    }
}
