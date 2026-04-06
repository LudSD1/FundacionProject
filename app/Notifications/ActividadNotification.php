<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ActividadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $actividad;
    protected $tipo; // 'creada', 'cierre_proximo'

    public function __construct($actividad, $tipo = 'creada')
    {
        $this->actividad = $actividad;
        $this->tipo = $tipo;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $actividad = $this->actividad->loadMissing('subtema.tema.curso');
        $curso = $actividad->subtema->tema->curso;

        if (!$curso) {
            Log::error("No se pudo encontrar el curso para la actividad ID: {$actividad->id}");
            return null; // O manejar de otra forma
        }

        $subject = $this->tipo == 'creada'
            ? "Nueva Actividad: {$actividad->titulo}"
            : "Recordatorio: La actividad '{$actividad->titulo}' cierra pronto";

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting("¡Hola, {$notifiable->name}!")
            ->line($this->tipo == 'creada'
                ? "Se ha publicado una nueva actividad en el curso: **{$curso->nombreCurso}**."
                : "Te recordamos que la actividad **'{$actividad->titulo}'** del curso **{$curso->nombreCurso}** está próxima a cerrar.")
            ->line("**Actividad:** {$actividad->titulo}")
            ->line("**Fecha Límite:** " . ($actividad->fecha_limite ? $actividad->fecha_limite->format('d/m/Y H:i') : 'Sin fecha límite'));

        if ($actividad->descripcion) {
            $message->line("**Descripción:** " . strip_tags($actividad->descripcion));
        }

        return $message
            ->action('Ver Actividad', route('Curso', $curso->codigoCurso ?? $curso->id))
            ->line('¡No pierdas la oportunidad de seguir aprendiendo!');
    }

    public function toDatabase($notifiable)
    {
        $actividad = $this->actividad->loadMissing('subtema.tema.curso');
        $curso = $actividad->subtema->tema->curso;

        if (!$curso) {
            return [
                'message' => "Actividad '{$actividad->titulo}' actualizada",
                'action' => '#',
                'type' => 'actividad_' . $this->tipo,
                'time' => Carbon::now()->diffForHumans(),
            ];
        }

        return [
            'message' => $this->tipo == 'creada'
                ? "Nueva actividad '{$actividad->titulo}' en {$curso->nombreCurso}"
                : "La actividad '{$actividad->titulo}' cierra pronto",
            'action' => route('Curso', $curso->codigoCurso ?? $curso->id),
            'type' => 'actividad_' . $this->tipo,
            'time' => Carbon::now()->diffForHumans(),
        ];
    }
}
