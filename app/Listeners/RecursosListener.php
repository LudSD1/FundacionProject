<?php

namespace App\Listeners;

use App\Models\Inscritos;
use App\Models\User;
use App\Notifications\RecursosNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecursosListener
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


        $recurso = $event->recurso;
        $action = $event->action;

        $administradores = User::role('Administrador')->get();

        

        $estudianteIds = Inscritos::where('cursos_id', $recurso->cursos->id)->withoutTrashed()->pluck('estudiante_id');

        $estudiantes = User::whereIn('id', $estudianteIds)->get();



        foreach ($estudiantes as $estudiante) {
            $estudiante->notify(new RecursosNotification($recurso, $action)); // Replace YourNotification with the notification class you want to send
        }

        $docente = $recurso->cursos->docente;

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
            $docente->notify(new RecursosNotification($recurso, $action));

                foreach ($administradores as $administrador) {
                    $administrador->notify(new RecursosNotification($recurso, $action));
                }


    }
}
