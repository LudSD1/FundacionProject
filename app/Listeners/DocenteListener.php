<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\DocenteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DocenteListener
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
        $docente = $event->docente;
        $tipoAccion = $event->tipoAccion;






        $administradores = User::role('Administrador')->get(); // Ajusta el criterio según tu aplicación

        // Aquí maneja la lógica para notificar a los diferentes destinatarios
                foreach ($administradores as $administrador) {
                    $administrador->notify(new DocenteNotification($docente, $tipoAccion));
                }
    }
}
