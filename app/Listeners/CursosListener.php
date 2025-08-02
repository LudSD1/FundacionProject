<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\CursoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CursosListener
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
        $curso = $event->curso;
        $action = $event->action;



        $administradores = User::role('Administrador')->get();

                foreach ($administradores as $administrador) {
                    $administrador->notify(new CursoNotification($curso, $action));
                }
        $docente = User::findOrFail($curso->docente_id);

        $docente->notify(new CursoNotification($curso, $action));





    }
}
