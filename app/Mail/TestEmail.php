<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $subject = "Este es un correo de prueba"; // Asunto del correo
    public $content; // Contenido dinámico

    /**
     * Create a new message instance.
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.test') // Vista del correo
                    ->subject($this->subject); // Asunto
    }



}
