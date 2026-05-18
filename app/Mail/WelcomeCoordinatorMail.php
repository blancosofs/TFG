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
     * Le añadimos un asunto (título) al correo electrónico
     */
    public function envelope(): \Illuminate\Mail\Mailables\Envelope
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Acceso a Edunoly',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Se queda con tu htmlString, sin usar archivos Blade adicionales
        return new Content(
            htmlString: "<h3>¡Hola {$this->user->name}!</h3><p>Tus claves de Edunoly son:</p><ul><li><strong>Usuario:</strong> {$this->user->email}</li><li><strong>Contraseña:</strong> {$this->pass}</li></ul>"
        );
    }
}