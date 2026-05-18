<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeCoordinatorMail;


class CoordinadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coordinadores = Coordinador::with('user')->get(); 
        return response()->json($coordinadores);
    }


    /**
     * Crea el coordinador de un colegio concreto (usado desde el panel admin).
     */
    public function storeForColegio(Request $request, $colegioId)
    {
        if (Coordinador::where('colegio_id', $colegioId)->exists()) {
            return response()->json(['ok' => false, 'mensaje' => 'Este colegio ya tiene un coordinador asignado.'], 422);
        }

        $request->validate([
            'nombre'    => 'required|string|max:25',
            'apellidos' => 'required|string|max:60',
            'email'     => 'required|email|unique:users,email',
            'telefono'  => 'nullable|string|max:20',
            'password'  => 'required|min:8',
        ]);

        try {
            DB::transaction(function () use ($request, $colegioId) {
                $user = User::create([
                    'name'       => $request->nombre,
                    'apellidos'  => $request->apellidos,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password),
                    'colegio_id' => $colegioId,
                    'activo'     => true,
                ]);

                Coordinador::create([
                    'colegio_id' => $colegioId,
                    'user_id'    => $user->id,
                ]);
                //mandamos el correo de bienvenida al coordinador recién creado
                Mail::to($user->email)->send(new WelcomeCoordinatorMail($user, $request->password));
            });

            return response()->json(['ok' => true, 'mensaje' => 'Coordinador creado con éxito']);

        } catch (\Exception $e) {
            Log::error('CoordinadorController@storeForColegio: ' . $e->getMessage());
            return response()->json(['ok' => false, 'mensaje' => 'No se pudo crear el coordinador. Inténtalo de nuevo.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validamos datos de ambas tablas
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'colegio_id' => 'required|integer'
        ]);

        // Usamos una Transacción por seguridad: o se crean los dos, o ninguno.
        DB::transaction(function () use ($request) {
            // A. Creamos el Usuario (donde va la contraseña)
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password), // <--- Encriptación
                'colegio_id'=> $request->colegio_id,
                'activo'    => true,
            ]);

            // B. Creamos el Coordinador usando el ID del usuario recién creado
            Coordinador::create([
                'colegio_id'     => $request->colegio_id,
                'user_id'        => $user->id, // <--- El puente entre ambos
            ]);
            //mail
            Mail::to($user->email)->send(new WelcomeCoordinatorMail($user, $request->password));
        });

        return response()->json(['ok' => true, 'mensaje' => 'Coordinador creado con éxito']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coordinador $coordinador)
    {
        // Actualizamos datos del coordinador
        $coordinador->update($request->only('colegio_id'));

        // Actualizamos datos del usuario vinculado
        $userData = $request->only('name', 'email');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $coordinador->user->update($userData);

            return response()->json(['ok' => true, 'mensaje' => 'Coordinador actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coordinador $coordinador)
    {
        $user = $coordinador->user;
        $coordinador->delete(); // Borra el registro en 'coordinadores'
        $user->delete();    // Borra el registro en 'users'
        
        return response()->json(['ok' => true, 'mensaje' => 'Coordinador eliminado']);
    }
}
