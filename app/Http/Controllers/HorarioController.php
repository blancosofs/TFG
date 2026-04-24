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
        $colegios = Colegio::all();
        return view ('colegios.index' ,compact ('colegios'));
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
        'nombre' => 'required|string|max:100',
        'entidad' => 'required|string|max:100',
        'direccion' => 'required|string|max:100',
        'activo' => 'required|boolean'
    ]);

    // Si todo está bien, lo guardamos
        Colegio::create($request->all());

        return redirect()->back()->with('success', 'Colegio creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colegio $colegio)
    {
        return view('colegios.show', compact('colegios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colegio $colegio)
    {
        return view('colegios.edit', compact('colegios'));
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
        return redirect()->route('colegios.index')->with('info', 'Clase eliminada');
    }
}
