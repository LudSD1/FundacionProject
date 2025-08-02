<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificadoGeneradoNotification extends Notification
{
    use Queueable;

    public $inscrito;
    public $codigo_certificado;

    public function __construct($inscrito, $codigo_certificado)
    {
        $this->inscrito = $inscrito;
        $this->codigo_certificado = $codigo_certificado;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('verificar.certificado', ['codigo' => $this->codigo_certificado]);

        return (new MailMessage)
            ->subject('Certificado Generado - ' . $this->inscrito->cursos->nombreCurso)
            ->view('emails.certificado_generado', [
                'inscrito' => $this->inscrito,
                'url' => $url,
            ]);
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
