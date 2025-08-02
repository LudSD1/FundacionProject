<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\UsuarioNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UsuarioListener
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

        $usuario = $event->usuario;
        $tipoAccion = $event->tipoAccion;




        $administradores = User::role('Administrador')->get(); // Ajusta el criterio según tu aplicación

        if($tipoAccion == 'login'){

            auth()->user()->notify(new UsuarioNotification($usuario, 'login2'));
        }

        foreach ($administradores as $administrador) {
            if ($administrador->id !== auth()->user()->id) { // Verifica que no sea el usuario autenticado
                $administrador->notify(new UsuarioNotification($usuario, $tipoAccion));
            }
        }
    }
}
