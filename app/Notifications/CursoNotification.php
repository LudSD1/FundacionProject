<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CursoNotification extends Notification
{
    use Queueable;

    protected $curso;
    protected $action;


    public function __construct($curso, $action)
    {
        $this->curso = $curso;
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





        $tiempo = Carbon::now()->diffForHumans();

        if ($this->action == 'crear') {
            $mensaje = 'Curso de ' . $this->curso->nombreCurso . ' creado!';
        }elseif ($this->action == 'modificado') {
            $mensaje = 'Curso de ' . $this->curso->nombreCurso . ' modificado!';

        }elseif ($this->action == 'borrado') {
            $mensaje = 'Curso de ' . $this->curso->nombreCurso . ' eliminado!';

        }elseif ($this->action == 'restaurado') {
            $mensaje = 'Curso de ' . $this->curso->nombreCurso . ' restaurado!';

        }

        return [
            'message' => $mensaje,
            'action' => route('ListadeCursos'),
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
