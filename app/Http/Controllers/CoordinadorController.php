<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use Illuminate\Http\Request;

class CoordinadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coordinadores = Coordinador::with('user')->get(); 
        return view('coordinadores.index', compact('coordinadores'));  //<--- Crea un array a partir de variables que ya existen.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coordinadores.create');
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
        });

        return redirect()->route('coordinadores.index')->with('success', 'Coordinador creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coordinador $coordinador)
    {
        return view('coordinadores.show', compact('coordinador'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coordinador $coordinador)
    {
        return view('coordinadores.edit', compact('coordinador'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coordinador $coordinador)
    {
        // Actualizamos datos del docente
        $coordinador->update($request->only('colegio_id'));

        // Actualizamos datos del usuario vinculado
        $userData = $request->only('name', 'email');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $docente->user->update($userData);

        return redirect()->route('coordinadores.index')->with('success', 'Coordinador actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coordinador $coordinador)
    {
        $user = $coordinador->user;
        $coordinador->delete(); // Borra el registro en 'coordinadores'
        $user->delete();    // Borra el registro en 'users'
        
        return redirect()->route('coordinadores.index')->with('success', 'Coordinador eliminado');
    }
}
