<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\EstudianteNotificaction;
use App\Notifications\EstudianteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EstudianteListener
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
    public function handle( $event)
    {

        $estudiante = $event->estudiante;
        $tutor = $event->tutor;
        $tipoAccion = $event->tipoAccion;






        $administradores = User::role('Administrador')->get(); // Ajusta el criterio según tu aplicación

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
                foreach ($administradores as $administrador) {
                    $administrador->notify(new EstudianteNotification($estudiante,$tutor, $tipoAccion));
                }

    }
}
