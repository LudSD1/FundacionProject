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
        return ActividadCompletion::updateOrCreate(
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
