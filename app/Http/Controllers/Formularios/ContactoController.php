<?php

namespace App\Http\Controllers\Formularios; //tienes que poner esto completo si estas en carpetas

use App\Http\Controllers\Controller; //aqui le pones la base o layout
use App\Mail\NuevoContactoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    // vamos a pillar todos los datos del formulario y enviarlos por email con nuestra clase Mailable (NuevoContactoMail)
    public function enviarConsulta(Request $request)
{
    // validamos pque no va a base de datos pero asi te ahorras lios
   $request->validate([
        'nombre' => 'required|string|max:25',
        'apellido' => 'required|string|max:25',
        'correo' => 'required|email|max:60',
        'telefono' => 'required',
        'perfil' => 'required',
        'codigo_Postal' => 'required|digits:5',
        'centro' => 'required|string|max:50',
        'seleccion2' => 'required', // El radio de ¿Eres usuario?
        'textarea' => 'nullable|string',
    ]);

    // pillamos todos 
    $datos = $request->all();

    // Enviamos el mail
    Mail::to('admin@demo.com')->send(new NuevoContactoMail($datos));

    //si funciona
    return back()->with('success', 'Solicitud enviada. Revisa Mailtrap!');
}
    
}