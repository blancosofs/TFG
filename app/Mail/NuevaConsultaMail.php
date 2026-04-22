<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class NuevaConsultaMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Declaramos la propiedad
    public $datos;

    public function __construct($datos)
    {
        // 2. Le asignamos los datos del formulario
        $this->datos = $datos;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consulta', // <--- COMPRUEBA QUE PONE ESTO EXACTAMENTE
        );
    }
}