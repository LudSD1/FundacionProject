<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\Inscrito;
use App\Events\ResourceViewed;
use App\Models\Completion;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function marcarVisto(Request $request, Recurso $recurso)
    {
        $inscrito = Inscrito::findOrFail($request->inscritos_id);

        // Verificar si ya está marcado como visto usando el modelo Completion
        $yaCompletado = Completion::where('inscrito_id', $inscrito->id)
            ->where('completable_id', $recurso->id)
            ->where('completable_type', get_class($recurso))
            ->exists();

        if (!$yaCompletado) {
            // Disparar el evento (el registro en Completion se hace en el listener)
            event(new ResourceViewed($inscrito, $recurso));

            return back()->with('success', '¡Recurso marcado como visto! Has ganado 10 XP.');
        }

        return back()->with('info', 'Este recurso ya estaba marcado como visto.');
    }
}
