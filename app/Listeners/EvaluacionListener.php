<?php

namespace App\Listeners;

use App\Models\Inscritos;
use App\Models\User;
use App\Notifications\EvaluacionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EvaluacionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        $evaluacion = $event->evaluacion;
        $action = $event->action;

        $administradores = User::role('Administrador')->get();

        $estudianteIds = Inscritos::where('cursos_id', $evaluacion->cursos->id)->pluck('estudiante_id');

        $estudiantes = User::whereIn('id', $estudianteIds)->get();



        foreach ($estudiantes as $estudiante) {
            $estudiante->notify(new EvaluacionNotification($evaluacion, $action)); // Replace YourNotification with the notification class you want to send
        }

        $docente = $evaluacion->cursos->docente;

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
            $docente->notify(new EvaluacionNotification($evaluacion, $action));

                foreach ($administradores as $administrador) {
                    $administrador->notify(new EvaluacionNotification($evaluacion, $action));
                }


    }
}
