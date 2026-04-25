<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tutores = Tutor::with('user')->get(); 
        return view('tutores.index', compact('tutores'));  //<--- Crea un array a partir de variables que ya existen.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tutores.create');
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
            'telefono' => 'required'
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

            // B. Creamos el Tutor usando el ID del usuario recién creado
            Tutor::create([
                'telefono'       => $request->telefono,
                'user_id'        => $user->id, // <--- El puente entre ambos
            ]);
        });

        return redirect()->route('tutores.index')->with('success', 'Tutor creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutor $tutor)
    {
        return view('tutores.show', compact('tutor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutor $tutor)
    {
        return view('tutores.edit', compact('tutor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutor $tutor)
    {
        // Actualizamos datos del tutor
        $tutor->update($request->only('colegio_id'));

        // Actualizamos datos del usuario vinculado
        $userData = $request->only('name', 'email');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $tutor->user->update($userData);

        return redirect()->route('tutores.index')->with('success', 'Tutor actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutor $tutor)
    {
        $user = $tutor->user;
        $tutor->delete(); // Borra el registro en 'tutores'
        $user->delete();    // Borra el registro en 'users'
        
        return redirect()->route('tutores.index')->with('success', 'Tutor eliminado');
    }
}
