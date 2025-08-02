<?php

namespace App\Events;

use App\Models\Inscritos;
use App\Models\Level;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLeveledUp implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inscrito;
    public $newLevel;

    public function __construct(Inscritos $inscrito, Level $newLevel)
    {
        $this->inscrito = $inscrito;
        $this->newLevel = $newLevel;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->inscrito->estudiante_id);
    }

    public function broadcastAs()
    {
        return 'user.leveled_up';
    }

    public function broadcastWith()
    {
        return [
            'level' => $this->newLevel->level_number,
            'message' => '¡Felicitaciones! Has alcanzado el nivel ' . $this->newLevel->level_number,
            'xp_for_next_level' => $this->getXPForNextLevel(),
        ];
    }

    protected function getXPForNextLevel()
    {
        $nextLevel = Level::where('level_number', '>', $this->newLevel->level_number)
            ->orderBy('level_number')
            ->first();

        return $nextLevel ? $nextLevel->required_xp : null;
    }
} 