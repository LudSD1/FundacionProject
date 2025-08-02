<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\Inscritos;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inscrito;
    public $achievement;

    public function __construct(Inscritos $inscrito, Achievement $achievement)
    {
        $this->inscrito = $inscrito;
        $this->achievement = $achievement;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->inscrito->estudiante_id);
    }

    public function broadcastAs()
    {
        return 'achievement.unlocked';
    }

    public function broadcastWith()
    {
        return [
            'achievement' => [
                'title' => $this->achievement->title,
                'description' => $this->achievement->description,
                'icon' => $this->achievement->icon,
                'xp_reward' => $this->achievement->xp_reward
            ],
            'message' => '¡Felicitaciones! Has desbloqueado el logro "' . $this->achievement->title . '"'
        ];
    }
} 