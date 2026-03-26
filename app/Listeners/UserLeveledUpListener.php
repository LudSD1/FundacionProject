<?php

namespace App\Listeners;

use App\Events\UserLeveledUp;
use Illuminate\Support\Facades\Log;

class UserLeveledUpListener
{
    /**
     * Handle the event.
     */
    public function handle(UserLeveledUp $event)
    {
        $inscrito = $event->inscrito;
        $newLevel = $event->newLevel;

        Log::info("Usuario subió de nivel", [
            'inscrito_id' => $inscrito->id,
            'estudiante_id' => $inscrito->estudiante_id,
            'nuevo_nivel' => $newLevel->level_number,
            'titulo_nivel' => $newLevel->title,
        ]);

        // Aquí puedes agregar notificaciones adicionales,
        // enviar emails, otorgar badges, etc.
    }
}
