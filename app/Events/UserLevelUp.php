<?php

namespace App\Events;

use App\Models\Inscritos;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLevelUp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inscrito;
    public $newLevel;
    public $oldLevel;

    /**
     * Create a new event instance.
     */
    public function __construct(Inscritos $inscrito, int $newLevel, int $oldLevel)
    {
        $this->inscrito = $inscrito;
        $this->newLevel = $newLevel;
        $this->oldLevel = $oldLevel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
