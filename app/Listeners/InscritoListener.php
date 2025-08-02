<?php

namespace App\Listeners;

use App\Events\InscritoEvent;
use App\Models\User;
use App\Notifications\InscritoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;


class InscritoListener
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
    public function handle(InscritoEvent $event)
    {
        $estudiante = $event->estudiante;
        $curso = $event->curso;
        $tipoAccion = $event->tipoAccion;




                $administradores = User::role('Administrador')->get(); // Ajusta el criterio según tu aplicación

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
                $estudiante->notify(new InscritoNotification($estudiante, $curso, $tipoAccion));
                $curso->docente->notify(new InscritoNotification($estudiante, $curso, $tipoAccion));
                foreach ($administradores as $administrador) {
                    $administrador->notify(new InscritoNotification($estudiante, $curso, $tipoAccion));
                }


    }
}
