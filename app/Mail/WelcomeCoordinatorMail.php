<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class WelcomeCoordinatorMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. ESTO ES LO QUE TE FALTA (Declarar que estas variables existen)
    public $user;
    public $pass;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $pass)
    {
        // 2. Aquí es donde les das valor
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ahora $this->pass ya no dará error porque está declarada arriba
        return new Content(
            htmlString: "Hola {$this->user->name}, tus claves de Edunoly son: " . $this->pass
        );
    }
}