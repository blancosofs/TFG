<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarios = Horario::all();
        return view ('horarios.index' ,compact ('horario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('horarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'docente_id' => 'required|integer|exists:docentes,id',
            'clase_id' => 'required|integer|exists:clases,id'
    ]);

    // Si todo está bien, lo guardamos
        Horario::create($request->all());

        return redirect()->back()->with('success', 'Horario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        return view('horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        return view('horarios.edit', compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $horario->update($request->all());
        return redirect()->route('horarios.index')->with('info', 'Datos del horario actualizados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('horarios.index')->with('info', 'Horario eliminada');
    }
}
