<?php

namespace App\Http\Controllers;

use App\Models\Colegio;
use Illuminate\Http\Request;

class ColegioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colegios = Colegio::with('coordinador.user')->get();

        if (request()->wantsJson()) {
            return response()->json($colegios->map(fn($c) => [
                'id'       => $c->id,
                'nombre'   => $c->nombre,
                'tipo'     => $c->tipo,
                'ciudad'   => $c->ciudad,
                'cp'       => $c->cp,
                'coordinador' => $c->coordinador ? [
                    'nombre'    => $c->coordinador->user->name,
                    'apellidos' => $c->coordinador->user->apellidos,
                    'email'     => $c->coordinador->user->email,
                ] : null,
            ]));
        }

        return view('colegios.index', compact('colegios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('colegios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'tipo'        => 'nullable|string|max:50',
            'etapas'      => 'nullable|string|max:100',
            'direccion'   => 'nullable|string|max:200',
            'ciudad'      => 'nullable|string|max:100',
            'comunidad'   => 'nullable|string|max:100',
            'cp'          => 'nullable|string|size:5|regex:/^\d{5}$/',
            'telefono'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:100',
            'web'         => 'nullable|url|max:255',
            'alumnos'     => 'nullable|integer|min:1',
            'notas'       => 'nullable|string|max:1000',
        ]);

        $colegio = Colegio::create([
            'nombre'      => $request->nombre,
            'tipo'        => $request->tipo,
            'etapas'      => $request->etapas,
            'direccion'   => $request->direccion,
            'ciudad'      => $request->ciudad,
            'comunidad'   => $request->comunidad,
            'cp'          => $request->cp,
            'telefono'    => $request->telefono,
            'email'       => $request->email,
            'web'         => $request->web,
            'num_alumnos' => $request->alumnos,
            'notas'       => $request->notas,
            'activo'      => true,
        ]);

        if (request()->wantsJson()) {
            return response()->json(['ok' => true, 'id' => $colegio->id, 'nombre' => $colegio->nombre]);
        }

        return redirect()->back()->with('success', 'Colegio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colegio $colegio)
    {
        return view('colegios.show', compact('colegio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colegio $colegio)
    {
        return view('colegios.edit', compact('colegio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Colegio $colegio)
    {
        $colegio->update($request->all());
        return redirect()->route('colegios.index')->with('info', 'Datos del colegio actualizados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colegio $colegio)
    {
        $colegio->delete();
        return redirect()->route('colegios.index')->with('info', 'Colegio eliminada');
    }
}
