<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstudianteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tipoAccion;
    public $tutor;
    public $estudiante;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($estudiante,$tutor, $tipoAccion)
    {
        $this->estudiante = $estudiante;
        $this->tutor = $tutor;
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
