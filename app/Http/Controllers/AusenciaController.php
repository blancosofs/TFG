<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use Illuminate\Http\Request;

class AusenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ausencias = Ausencia::all();
        return view ('ausencias.index' ,compact ('ausencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('ausencias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'fecha' => 'required|date',
        'tipo' => 'required|in:falta,retraso',
        'justificada' => 'required|boolean',
        'justificacion' => 'nullable|string|max:255',
        'alumno_id' => 'required|integer|exists:alumnos,id',
        'docente_id' => 'required|integer|exists:docentes,id',
        'horario_id' => 'required|integer|exists:horarios,id',
    ]);

    // Si todo está bien, lo guardamos
    Ausencia::create($request->all());

    return redirect()->back()->with('success', 'Falta registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ausencia $ausencia)
    {
         return view('ausencias.show', compact('ausencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ausencia $ausencia)
    {
        return view('ausencias.edit', compact('ausencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ausencia $ausencia)
    {
        $ausencia->update($request->all());
        return redirect()->route('ausencias.index')->with('info', 'Datos de la ausencia actualizados');
        //Recibe los nuevos datos del formulario de edit() y sobrescribe los antiguos en MySQL.
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ausencia $ausencia)
    {
        $ausencia->delete();
        return redirect()->route('ausencias.index')->with('info', 'Ausencia eliminada');
    }
}
