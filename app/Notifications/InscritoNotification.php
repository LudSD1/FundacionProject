<?php

namespace App\Notifications;

use App\Models\Inscritos;
use Carbon\Carbon;
use FontLib\Table\Type\post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscritoNotification extends Notification
{
    use Queueable;


    protected $estudiante;
    protected $curso;
    protected $tipoAccion;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($estudiante, $curso, $tipoAccion)
    {
        $this->estudiante = $estudiante;
        $this->curso = $curso;
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

        if ($this->tipoAccion == 'inscripcion') {
            $mensaje = '¡Estudiante ' . $this->estudiante->name . ' inscrito exitosamente en el curso ' . $this->curso->nombreCurso. '!';
        }elseif ($this->tipoAccion == 'eliminacion') {
            $mensaje = '¡Estudiante ' . $this->estudiante->name . ' retirado exitosamente del curso ' . $this->curso->nombreCurso. '!';
        }
        elseif ($this->tipoAccion == 'restauracion') {
            $mensaje = '¡Estudiante ' . $this->estudiante->name . ' restaurado exitosamente al curso ' . $this->curso->nombreCurso. '!';
        }

        return [
            'message' => $mensaje,
            'action' => route('ListaEstudiantes', $this->curso->id),
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

        ];
    }
}
