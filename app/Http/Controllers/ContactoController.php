<?php

// app/Http/Controllers/ContactoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaConsultaMail;

class ContactoController extends Controller
{
    public function enviarConsulta(Request $request)
    {
        // Cogemos todos los datos del formulario (nombre, email, perfil, centro, etc.)
        $datos = $request->all();

        // Te mandas el mail a ti misma (administrador@edunoly.com)
        Mail::to('administrador@edunoly.com')->send(new NuevaConsultaMail($datos));

        
        return back()->with('success', 'Gracias. Hemos recibido tu consulta.');
    }
    
}