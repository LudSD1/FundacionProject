<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstudianteNotification extends Notification
{
    use Queueable;

    protected $estudiante;
    protected $tutor;
    protected $tipoAccion;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($estudiante,$tutor, $tipoAccion)
    {
        $this->estudiante = $estudiante;
        $this->tutor = $tutor;
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {

        $tiempo = Carbon::now()->diffForHumans();

        if ($this->tipoAccion == 'registro' ) {
            $mensaje = '¡Estudiante ' . $this->estudiante->name .' '.  $this->estudiante->lastname1 .' '.  $this->estudiante->lastname2 . ' Registrado';
        }

        return [
            'message' => $mensaje,
            'action' => route('ListaEstudiantes'),
            'time' => $tiempo,
        ];
    }



    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
        ->line('Bienvenido al Sistema de Cursos de la Fundación Educar Para La Vida.')
        ->line('Sus credenciales son:')
        ->line('Correo: ' . $this->estudiante->email)
        ->line('Contraseña: ' . substr($this->estudiante->name, 0, 1) . substr($this->estudiante->lastname1, 0, 1) . substr($this->estudiante->lastname2, 0, 1) . $this->estudiante->CI)
        ->action('Link del Sitio', url('https://educarparalavida.org.bo/cursos/public/login'))
        ->line('Gracias por su atención!');

            // Condicionalmente agregar CC
            if ($this->tutor && !empty($this->tutor->CorreoElectronicoTutor)) {
            $mailMessage->cc($this->tutor->CorreoElectronicoTutor);
            }

            return $mailMessage;
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
