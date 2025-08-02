<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $actionText = 'Verificar Correo Electrónico';

        // Generar URL firmada y segura para verificación
        $actionUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60), // Expira en 60 minutos
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $esVerificacion = Str::contains($actionText, 'Verificar');

        return (new MailMessage)
            ->subject('Verifica tu correo electrónico')
            ->view('vendor.notifications.email', [
                'actionText' => $actionText,
                'actionUrl' => $actionUrl,
                'esVerificacion' => $esVerificacion,
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
