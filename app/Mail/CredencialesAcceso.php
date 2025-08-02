<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesAcceso extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $passwordPlain;

    public function __construct($user, $passwordPlain)
    {
        $this->user = $user;
        $this->passwordPlain = $passwordPlain;
    }

    public function build()
    {
        return $this->subject('Tus credenciales de acceso')
                    ->view('emails.credenciales');
    }
}