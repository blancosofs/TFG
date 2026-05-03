<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. ¿El Frontend nos está pidiendo las clases de un curso específico?
        // (Ejemplo de petición del JS: fetch('/api/clases?curso_id=5'))
        if ($request->has('curso_id')) {
            $clases = Clase::where('curso_id', $request->curso_id)->get();
            return response()->json($clases);
        }

        // 2. Si no pide un curso específico, buscamos todas las clases del colegio.
        // Como la tabla 'clases' no tiene 'colegio_id', filtramos a través de la tabla 'cursos'
        $colegioId = Auth::user()->colegio_id;
        
        $clases = Clase::whereHas('curso', function ($query) use ($colegioId) {
            $query->where('colegio_id', $colegioId);
        })->get();

        return response()->json($clases);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('clases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string|max:10',
        'codigo_acceso' => 'nullable|string|max:10',
        'curso_id' => 'required|integer|exists:cursos,id',
    ]);

    // Si todo está bien, lo guardamos
        Clase::create($request->all());

        return redirect()->back()->with('success', 'Clase creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clase $clase)
    {
        return view('clases.show', compact('clase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
    {
        return view('clases.edit', compact('clase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clase $clase)
    {
        $clase->update($request->all());
        return redirect()->route('clases.index')->with('info', 'Datos de la clase actualizados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clase $clase)
    {
        $clase->delete();
        return redirect()->route('clases.index')->with('info', 'Clase eliminada');
    }
}
