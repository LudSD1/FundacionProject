<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;


class NuevoUsuarioRegistrado extends Mailable
{

    public $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->view('email')
                    ->subject('Bienvenido a nuestra plataforma');
    }

}
