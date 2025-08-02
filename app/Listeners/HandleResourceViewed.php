<?php

namespace App\Listeners;

use App\Events\ResourceViewed;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Events\UserLevelUp;
use App\Models\Completion;

class HandleResourceViewed
{
    public function handle(ResourceViewed $event)
    {
        $inscrito = $event->inscrito;
        $recurso = $event->recurso;

        // Crear el registro de completion
        Completion::create([
            'inscrito_id' => $inscrito->id,
            'completable_id' => $recurso->id,
            'completable_type' => get_class($recurso),
            'completed_at' => now(),
            'xp_gained' => 10 // XP base por ver un recurso
        ]);

        // Actualizar experiencia y nivel del inscrito
        $nivelActual = $inscrito->nivel;
        $inscrito->experiencia += 10;

        // Verificar si el estudiante sube de nivel
        $xpNecesariaParaNivel = 100; // XP base necesaria para cada nivel
        $nuevoNivel = floor($inscrito->experiencia / $xpNecesariaParaNivel) + 1;

        if ($nuevoNivel > $nivelActual) {
            $inscrito->nivel = $nuevoNivel;
            event(new UserLevelUp($inscrito));
        }

        $inscrito->save();

        // Verificar y otorgar logros
        $this->checkResourceAchievements($inscrito);
    }

    private function checkResourceAchievements($inscrito)
    {
        // Contar recursos completados usando el modelo Completion
        $recursosVistos = Completion::where('inscrito_id', $inscrito->id)
            ->where('completable_type', 'App\Models\Recurso')
            ->count();

        // Definir los logros relacionados con recursos
        $achievements = [
            ['nombre' => 'Primer Recurso', 'descripcion' => 'Has visto tu primer recurso', 'condicion' => 1],
            ['nombre' => 'Estudiante Dedicado', 'descripcion' => 'Has visto 5 recursos', 'condicion' => 5],
            ['nombre' => 'Maestro de Recursos', 'descripcion' => 'Has visto 10 recursos', 'condicion' => 10],
            ['nombre' => 'Experto en Contenido', 'descripcion' => 'Has visto 25 recursos', 'condicion' => 25],
        ];

        foreach ($achievements as $achievementData) {
            if ($recursosVistos >= $achievementData['condicion']) {
                $achievement = Achievement::firstOrCreate([
                    'nombre' => $achievementData['nombre'],
                    'descripcion' => $achievementData['descripcion']
                ]);

                // Verificar si el usuario ya tiene este logro
                if (!UserAchievement::where('inscrito_id', $inscrito->id)
                    ->where('achievement_id', $achievement->id)
                    ->exists()) {

                    // Otorgar el logro
                    UserAchievement::create([
                        'inscrito_id' => $inscrito->id,
                        'achievement_id' => $achievement->id,
                        'fecha_obtencion' => now()
                    ]);
                }
            }
        }
    }
}
