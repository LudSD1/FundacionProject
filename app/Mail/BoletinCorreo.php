<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BoletinCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public $inscritos;
    public $boletin;
    public $boletinNotas;

    public function __construct($inscritos, $boletin, $boletinNotas)
    {
        $this->inscritos = $inscritos;
        $this->boletin = $boletin;
        $this->boletinNotas = $boletinNotas;
    }

    public function build()
    {
        return $this->subject('Asunto del correo')
                    ->view('Estudiante.boletin2');
    }
}
