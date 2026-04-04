<?php

namespace App\Traits;

use App\Models\NotaEntrega;
use App\Models\IntentoCuestionario;
use App\Models\ActividadCompletion;
use App\Models\Actividad;
use Illuminate\Support\Facades\DB;

trait CalificacionTrait
{
    protected function marcarActividadCompletada($actividad, $inscripcionId)
    {
        $completion = ActividadCompletion::updateOrCreate(
            [
                'completable_type' => Actividad::class,
                'completable_id' => $actividad->id,
                'inscritos_id' => $inscripcionId,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        // Verificar logro MODULE_MASTER (actividades/módulos completados)
        try {
            $inscrito = \App\Models\Inscritos::find($inscripcionId);
            if ($inscrito) {
                $achievementService = app(\App\Services\AchievementService::class);

                $totalCompletadas = ActividadCompletion::where('inscritos_id', $inscripcionId)
                    ->where('completed', true)
                    ->count();
                $achievementService->checkAndAwardAchievements($inscrito, 'MODULE_MASTER', $totalCompletadas);

                // Verificar racha
                $streak = $achievementService->calculateStreak($inscrito);
                $achievementService->checkAndAwardAchievements($inscrito, 'STREAK_MASTER', $streak);
            }
        } catch (\Exception $e) {
            \Log::warning('Error checking MODULE_MASTER achievement: ' . $e->getMessage());
        }

        return $completion;
    }

    protected function verificarCalificacionActividad($actividad, $inscripcionId)
    {
        // Si es un congreso, se marca como completado directamente
        if ($actividad->subtema->tema->curso->tipo === 'congreso') {
            return true;
        }

        // Verificar nota de entrega para actividades normales
        if ($actividad->tipoActividad->nombre !== 'Cuestionario') {
            return NotaEntrega::where('inscripcion_id', $inscripcionId)
                ->where('actividad_id', $actividad->id)
                ->exists();
        }



        // Verificar intento de cuestionario
        return DB::table('intentos_cuestionarios')
            ->join('cuestionarios', 'intentos_cuestionarios.cuestionario_id', '=', 'cuestionarios.id')
            ->where('cuestionarios.actividad_id', $actividad->id)
            ->where('intentos_cuestionarios.inscrito_id', $inscripcionId) // CORREGIDO
            ->whereNotNull('intentos_cuestionarios.finalizado_en')
            ->exists();
    }
}
