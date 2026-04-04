<?php

namespace App\Listeners;

use App\Events\ResourceViewed;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Events\UserLevelUp;
use App\Models\Completion;
use App\Services\AchievementService;

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

        // Verificar y otorgar logros usando AchievementService
        $this->checkResourceAchievements($inscrito);
    }

    private function checkResourceAchievements($inscrito)
    {
        try {
            // Contar recursos completados usando el modelo Completion
            $recursosVistos = Completion::where('inscrito_id', $inscrito->id)
                ->where('completable_type', 'App\Models\Recurso')
                ->count();

            $achievementService = app(AchievementService::class);
            $achievementService->checkAndAwardAchievements($inscrito, 'RESOURCE_EXPLORER', $recursosVistos);

            // Verificar racha
            $streak = $achievementService->calculateStreak($inscrito);
            $achievementService->checkAndAwardAchievements($inscrito, 'STREAK_MASTER', $streak);
        } catch (\Exception $e) {
            \Log::warning('Error checking RESOURCE_EXPLORER achievement: ' . $e->getMessage());
        }
    }
}
