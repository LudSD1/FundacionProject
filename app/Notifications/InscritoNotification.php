<?php

namespace App\Notifications;

use App\Models\Inscritos;
use Carbon\Carbon;
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
     * Tipos de acción soportados:
     * - inscripcion: Cuando un estudiante se inscribe o es inscrito
     * - eliminacion: Cuando se retira la inscripción
     * - restauracion: Cuando se restaura la inscripción
     * - completado: Cuando el estudiante completa el curso
     * - pago_completado: Cuando se confirma el pago del estudiante
     */
    public function __construct($estudiante, $curso, $tipoAccion)
    {
        $this->estudiante = $estudiante;
        $this->curso = $curso;
        $this->tipoAccion = $tipoAccion;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Genera mensaje y metadata según la acción y quién recibe la notificación.
     */
    public function toDatabase($notifiable)
    {
        $esEstudiante = $notifiable->id === $this->estudiante->id;
        $nombreEstudiante = $this->estudiante->name;
        $nombreCurso = $this->curso->nombreCurso;
        $tipoCurso = ucfirst($this->curso->tipo ?? 'curso');

        // Determinar icono, mensaje y detalles según el tipo de acción
        switch ($this->tipoAccion) {
            case 'inscripcion':
                $icon = 'bi-person-plus-fill';
                if ($esEstudiante) {
                    $mensaje = "¡Te has inscrito exitosamente en \"{$nombreCurso}\"!";
                    $detalles = "Ya formas parte del {$tipoCurso} \"{$nombreCurso}\". Revisa el contenido y comienza tu aprendizaje.";
                } else {
                    $mensaje = "¡Estudiante {$nombreEstudiante} inscrito en \"{$nombreCurso}\"!";
                    $detalles = "El estudiante {$nombreEstudiante} se ha inscrito en el {$tipoCurso} \"{$nombreCurso}\".";
                }
                break;

            case 'eliminacion':
                $icon = 'bi-person-dash-fill';
                if ($esEstudiante) {
                    $mensaje = "Tu inscripción en \"{$nombreCurso}\" ha sido retirada.";
                    $detalles = "Se ha retirado tu inscripción del {$tipoCurso} \"{$nombreCurso}\". Si crees que esto es un error, contacta al administrador.";
                } else {
                    $mensaje = "Estudiante {$nombreEstudiante} retirado de \"{$nombreCurso}\".";
                    $detalles = "Se retiró la inscripción de {$nombreEstudiante} del {$tipoCurso} \"{$nombreCurso}\".";
                }
                break;

            case 'restauracion':
                $icon = 'bi-arrow-counterclockwise';
                if ($esEstudiante) {
                    $mensaje = "¡Tu inscripción en \"{$nombreCurso}\" ha sido restaurada!";
                    $detalles = "Tu inscripción en el {$tipoCurso} \"{$nombreCurso}\" fue restaurada. Ya puedes acceder nuevamente al contenido.";
                } else {
                    $mensaje = "Estudiante {$nombreEstudiante} restaurado en \"{$nombreCurso}\".";
                    $detalles = "Se restauró la inscripción de {$nombreEstudiante} al {$tipoCurso} \"{$nombreCurso}\".";
                }
                break;

            case 'completado':
                $icon = 'bi-trophy-fill';
                if ($esEstudiante) {
                    $mensaje = "🎉 ¡Felicidades! Has completado \"{$nombreCurso}\".";
                    $detalles = "Has completado exitosamente el {$tipoCurso} \"{$nombreCurso}\". ¡Sigue así!";
                } else {
                    $mensaje = "Estudiante {$nombreEstudiante} completó \"{$nombreCurso}\".";
                    $detalles = "{$nombreEstudiante} ha finalizado todos los requisitos del {$tipoCurso} \"{$nombreCurso}\".";
                }
                break;

            case 'pago_completado':
                $icon = 'bi-credit-card-2-front-fill';
                if ($esEstudiante) {
                    $mensaje = "✅ Tu pago para \"{$nombreCurso}\" ha sido confirmado.";
                    $detalles = "El pago del {$tipoCurso} \"{$nombreCurso}\" fue verificado correctamente. Ya tienes acceso completo.";
                } else {
                    $mensaje = "Pago confirmado de {$nombreEstudiante} en \"{$nombreCurso}\".";
                    $detalles = "Se confirmó el pago de {$nombreEstudiante} para el {$tipoCurso} \"{$nombreCurso}\".";
                }
                break;

            default:
                $icon = 'bi-info-circle-fill';
                $mensaje = "Actualización de inscripción en \"{$nombreCurso}\".";
                $detalles = "Se realizó una actualización en la inscripción de {$nombreEstudiante} en \"{$nombreCurso}\".";
                break;
        }

        return [
            'message' => $mensaje,
            'details' => $detalles,
            'icon' => $icon,
            'action' => route('ListaEstudiantes', $this->curso->id),
            'time' => Carbon::now()->diffForHumans(),
            'tipo_accion' => $this->tipoAccion,
            'curso_id' => $this->curso->id,
            'estudiante_id' => $this->estudiante->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
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
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
