<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocenteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tipoAccion;
    public $docente;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($docente, $tipoAccion)
    {

        $this->docente = $docente;
        $this->tipoAccion = $tipoAccion;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
