<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TareaNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     protected $tarea;
     protected $action;

    public function __construct($tarea, $action)
    {
        $this->tarea = $tarea;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $this->tarea->loadMissing('cursos');
        $curso = $this->tarea->cursos;

        $tiempo = Carbon::now()->diffForHumans();

        if ($this->action == 'crear') {
            $mensaje = 'Tarea ' . $this->tarea->titulo_tarea . ' creada en el curso ' . ($curso->nombreCurso ?? 'N/A') . '!';
        }

        return [
            'message' => $mensaje ?? 'Nueva tarea disponible',
            'action' => $curso ? route('Curso', $curso->codigoCurso ?? $curso->id) : '#',
            'time' => $tiempo,
        ];
    }




    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
