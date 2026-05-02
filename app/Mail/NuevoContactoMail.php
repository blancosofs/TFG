<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class NuevoContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    //variable de datos propia
    public $datos;

    public function __construct($datos)
    {
        // asignamos a la variable de datos propia el valor que nos llega por el constructor
        $this->datos = $datos;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consulta', 
        );
    }
}