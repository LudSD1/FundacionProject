<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InscritoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estudiante;
    public $curso;
    public $tipoAccion;

    public function __construct($estudiante , $curso, $tipoAccion)
    {
        $this->estudiante = $estudiante;
        $this->curso = $curso;
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
