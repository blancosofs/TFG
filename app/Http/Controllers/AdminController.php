<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colegio;
use App\Models\User;
use App\Models\Coordinador;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeCoordinatorMail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function registrarCentro(Request $request)
    {
        // 1. Validar
        $request->validate(['coord_email' => 'required|email|unique:users,email']);

        // 2. Ejecutar el Alta
        DB::transaction(function () use ($request) {
            // A. Crear Colegio
            $colegio = Colegio::create([
                'nombre' => $request->colegio_nombre,
                'entidad' => $request->colegio_entidad,
                'direccion' => $request->colegio_direccion,
            ]);

            // B. Crear Usuario Coordinador
            $pass = Str::random(10); // Generamos la clave
            $user = User::create([
                'name' => $request->coord_nombre,
                'apellidos' => $request->coord_apellido,
                'email' => $request->coord_email,
                'password' => Hash::make($pass),
                'colegio_id' => $colegio->id,
            ]);

            // C. Vincular en la tabla de coordinadores
            Coordinador::create([
                'colegio_id' => $colegio->id,
                'user_id' => $user->id,
            ]);

            // D. Enviar Email con las credenciales al nuevo coordinador
            Mail::to($user->email)->send(new WelcomeCoordinatorMail($user, $pass));
        });

        return back()->with('success', '¡Colegio y Coordinador creados! Email enviado.');
    }
}
