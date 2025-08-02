<?php

namespace App\Listeners;

use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\User;
use App\Notifications\TareaNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TareaListener
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
        $tarea = $event->tarea;
        $action = $event->action;

        $administradores = User::role('Administrador')->get();

        $estudianteIds = Inscritos::where('cursos_id', $tarea->cursos->id)->pluck('estudiante_id');

        $estudiantes = User::whereIn('id', $estudianteIds)->get();

      

        foreach ($estudiantes as $estudiante) {
            $estudiante->notify(new TareaNotifications($tarea, $action)); // Replace YourNotification with the notification class you want to send
        }

        $docente = $tarea->cursos->docente;

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
            $docente->notify(new TareaNotifications($tarea, $action));

                foreach ($administradores as $administrador) {
                    $administrador->notify(new TareaNotifications($tarea, $action));
                }


    }
    }
