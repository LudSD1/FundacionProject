<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsuarioNotification extends Notification
{
    use Queueable;

    protected $usuario;
    protected $tipoAccion;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($usuario, $tipoAccion)
    {
        $this->usuario = $usuario;
        $this->tipoAccion = $tipoAccion;
        //
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

        if ($this->tipoAccion == 'eliminacion') {
            $mensaje = $this->usuario->roles->pluck('name')[0] . ' ' . $this->usuario->name. ' ' . $this->usuario->lastname1 . ' '. $this->usuario->lastname2 . ' ha sido borrado';
        }elseif($this->tipoAccion == 'modificacion'){
            $mensaje = $this->usuario->roles->pluck('name')[0] . ' ' . $this->usuario->name. ' ' . $this->usuario->lastname1 . ' '. $this->usuario->lastname2 . ' ha sido modificado';
        }elseif($this->tipoAccion == 'restaurar'){
            $mensaje = $this->usuario->roles->pluck('name')[0] . ' ' . $this->usuario->name. ' ' . $this->usuario->lastname1 . ' '. $this->usuario->lastname2 . ' ha sido restaurado';
        }elseif($this->tipoAccion == 'login'){
            $mensaje = $this->usuario->roles->pluck('name')[0] . ' ' . $this->usuario->name. ' ' . $this->usuario->lastname1 . ' '. $this->usuario->lastname2 . ' inició sesion.';
        }elseif($this->tipoAccion == 'login2'){
            $mensaje = 'Iniciaste Sesión, Bienvenido';
        }

        return [
            'message' => $mensaje,
            'action' => route('perfil', $this->usuario->id),
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
