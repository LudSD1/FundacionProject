<?php

namespace App\Services;

use App\Models\User;
use App\Models\Inscritos;
use App\Events\UserLevelUp;
use Illuminate\Support\Facades\Cache;

class XPService
{
    // Niveles y XP requerida
    private $levels = [
        1 => 0,
        2 => 100,
        3 => 250,
        4 => 500,
        5 => 1000,
        6 => 2000,
        7 => 3500,
        8 => 5000,
        9 => 7500,
        10 => 10000
    ];

    /**
     * Añade XP a un usuario inscrito
     */
    public function addXP(Inscritos $inscrito, int $amount, string $reason)
    {
        // Obtener XP actual del cache o DB
        $currentXP = Cache::remember(
            "user_xp:{$inscrito->id}",
            now()->addHours(24),
            fn() => $inscrito->xp ?? 0
        );

        // Calcular nuevo XP
        $newXP = $currentXP + $amount;

        // Actualizar en DB
        $inscrito->update(['xp' => $newXP]);

        // Limpiar cache
        Cache::forget("user_xp:{$inscrito->id}");
        Cache::forget("user_level:{$inscrito->id}");

        // Verificar si subió de nivel
        $oldLevel = $this->getCurrentLevel($currentXP);
        $newLevel = $this->getCurrentLevel($newXP);

        if ($newLevel > $oldLevel) {
            // Trigger evento de subida de nivel
            event(new UserLevelUp($inscrito, $newLevel, $oldLevel));
        }

        // Registrar la ganancia de XP
        $this->logXPGain($inscrito, $amount, $reason);

        return [
            'old_xp' => $currentXP,
            'new_xp' => $newXP,
            'gained' => $amount,
            'old_level' => $oldLevel,
            'new_level' => $newLevel,
            'leveled_up' => $newLevel > $oldLevel
        ];
    }

    /**
     * Obtiene el nivel actual basado en XP
     */
    public function getCurrentLevel(int $xp): int
    {
        foreach ($this->levels as $level => $requiredXP) {
            if ($xp < $requiredXP) {
                return $level - 1;
            }
        }
        return max(array_keys($this->levels));
    }

    /**
     * Obtiene XP necesaria para el siguiente nivel
     */
    public function getNextLevelXP(int $currentXP): int
    {
        $currentLevel = $this->getCurrentLevel($currentXP);
        return $this->levels[$currentLevel + 1] ?? $this->levels[max(array_keys($this->levels))];
    }

    /**
     * Registra la ganancia de XP
     */
    private function logXPGain(Inscritos $inscrito, int $amount, string $reason)
    {
        \DB::table('xp_events')->insert([
            'users_id' => $inscrito->estudiante_id,
            'curso_id' => $inscrito->cursos_id,
            'xp' => $amount,
            'origen_type' => 'system',
            'origen_id' => 0,
            'xp_event_type_id' => 1, // ID del tipo de evento por defecto
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Obtiene el ranking del usuario
     */
    public function getUserRank(Inscritos $inscrito): int
    {
        $key = "user_rank:{$inscrito->id}";

        return Cache::remember($key, now()->addMinutes(30), function () use ($inscrito) {
            return Inscritos::where('xp', '>', $inscrito->xp)->count() + 1;
        });
    }

    /**
     * Obtiene las estadísticas de XP del usuario
     */
    public function getUserStats(Inscritos $inscrito): array
    {
        $currentXP = $inscrito->xp;
        $currentLevel = $this->getCurrentLevel($currentXP);
        $nextLevelXP = $this->getNextLevelXP($currentXP);

        return [
            'current_xp' => $currentXP,
            'current_level' => $currentLevel,
            'next_level_xp' => $nextLevelXP,
            'progress_percentage' => ($currentXP / $nextLevelXP) * 100,
            'rank' => $this->getUserRank($inscrito)
        ];
    }
}
