<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocenteNotification extends Notification
{
    use Queueable;


    protected $tipoAccion;
    protected $docente;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($docente, $tipoAccion)
    {
        $this->docente = $docente;
        $this->tipoAccion = $tipoAccion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return ['mail' ,'database'];
    }


    public function toDatabase($notifiable)
    {





        $tiempo = Carbon::now()->diffForHumans();

        if ($this->tipoAccion == 'registro') {
            $mensaje = '¡Docente ' .$this->docente->name .' '. $this->docente->lastname1 .' '. $this->docente->lastname2 . ' Registrado';
        }

        return [
            'message' => $mensaje,
            'action' => route('ListaEstudiantes'),
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
                    ->line('Bienvenido al Sistema de Cursos de la Fundación Educar Para La Vida.')
                    ->line('Sus credenciales son.')
                    ->line('Correo: '. $this->docente->email)
                    ->line('Contraseña:'. substr($this->docente->name,0,1).substr($this->docente->lastname1,0,1).substr($this->docente->lastname2,0,1).$this->docente->CI)
                    ->action('Link del Sitio', url('https://educarparalavida.org.bo/cursos/public/login'))
                    ->line('`Gracias por su atención`!');
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
