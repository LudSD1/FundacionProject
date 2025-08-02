<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForoNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $foro;
    protected $action;
    public function __construct($foro, $action)
    {
    $this->foro = $foro;
    $this->action = $action;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        // dd([
        //     'estudiante' => $this->estudiante,
        //     'curso' => $this->curso,
        //     'tipoAccion' => $this->tipoAccion,
        // ]);

        $tiempo = Carbon::now()->diffForHumans();

        if ($this->action == 'crear') {
            $mensaje = 'Foro ' . $this->foro->nombreForo . ' creada en el curso ' . $this->foro->cursos->nombreCurso . '!';
        }

        return [
            'message' => $mensaje,
            'action' => route('Curso', $this->foro->cursos_id),
            'time' => $tiempo,
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
  

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
