<?php

namespace App\Http\Controllers;

use App\Models\ActividadCompletion;
use App\Models\Cuestionario;
use App\Models\Inscritos;
use App\Models\Tareas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadCompletionController extends Controller
{

    protected function marcarActividadCompletada(Request $request, string $modelClass, $actividadId, string $nombreActividad)
    {
        $request->validate([
            'inscritos_id' => 'required|exists:inscritos,id'
        ]);

        $actividad = $modelClass::findOrFail($actividadId);
        $cursoId = $actividad->subtema->tema->curso->id;

        $inscripcion = Inscritos::where('id', $request->inscritos_id)
                        ->where('cursos_id', $cursoId)
                          ->firstOrFail();

        ActividadCompletion::updateOrCreate(
            [
                'completable_type' => $modelClass,
                'completable_id' => $actividad->id,
                'inscritos_id' => $inscripcion->id,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        return redirect()->back()->with('success', $nombreActividad . ' marcado como completado.');
    }
}
