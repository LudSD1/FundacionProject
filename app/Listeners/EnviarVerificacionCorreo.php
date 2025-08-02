<?php

namespace App\Listeners;

use App\Events\UsuarioRegistrado;
use App\Notifications\VerificacionCorreoPersonalizada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarVerificacionCorreo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UsuarioRegistrado $event): void
    {
        //
        $event->user->notify(new VerificacionCorreoPersonalizada());
    }
}
