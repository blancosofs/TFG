<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clases = Clase::all();
        return view ('clases.index' ,compact ('clases'));
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
        'curso_id' => 'required|integer|exists:alumnos,id',
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
        return view('clases.show', compact('clases'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
    {
        return view('clases.edit', compact('clases'));
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
