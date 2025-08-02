<?php

namespace App\Events;

use App\Models\Inscrito;
use App\Models\Recurso;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResourceViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inscrito;
    public $recurso;

    public function __construct(Inscrito $inscrito, Recurso $recurso)
    {
        $this->inscrito = $inscrito;
        $this->recurso = $recurso;
    }
}
