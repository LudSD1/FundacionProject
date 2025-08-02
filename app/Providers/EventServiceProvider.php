<?php

namespace App\Providers;

use App\Events\UsuarioRegistrado;
use App\Listeners\EnviarVerificacionCorreo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ResourceViewed;
use App\Listeners\HandleResourceViewed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UsuarioRegistrado::class => [
            EnviarVerificacionCorreo::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\InscritoEvent' => [
            'App\Listeners\InscritoListener',
        ],
        'App\Events\EstudianteEvent' => [
            'App\Listeners\EstudianteListener',
        ],
        'App\Events\DocenteEvent' => [
            'App\Listeners\DocenteListener',
        ],
        'App\Events\UsuarioEvent' => [
            'App\Listeners\UsuarioListener',
        ],
        'App\Events\CursoEvent' => [
            'App\Listeners\CursosListener',
        ],
        'App\Events\TareaEvent' => [
            'App\Listeners\TareaListener',
        ],
        'App\Events\EvaluacionEvent' => [
            'App\Listeners\EvaluacionListener',
        ],
        'App\Events\ForoEvent' => [
            'App\Listeners\ForoListener',
        ],
        'App\Events\RecursosEvent' => [
            'App\Listeners\RecursosListener',
        ],
        'App\Events\UserLeveledUp' => [
            'App\Listeners\UserLeveledUpListener',
        ],
        ResourceViewed::class => [
            HandleResourceViewed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
