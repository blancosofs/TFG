<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::all();
        return view ('cursos.index' ,compact ('curso'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('cursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request) {
        // 1. Validamos los datos
        $request->validate([
            'nombre' => 'required|string|max:30',
            'colegio_id' => 'required|exists:colegios,id'
        ]);

        // 2. Creamos el curso en la base de datos
        Curso::create([
            'nombre' => $request->nombre,
            'colegio_id' => $request->colegio_id,
        ]);

        // 3. Redirigimos a la página donde se ven los cursos
        return redirect()->route('coordinador.configuracion')->with('status', 'Curso creado');
    }


    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        return view('cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        $curso->update($request->all());
        return redirect()->route('cursos.index')->with('info', 'Datos del curso actualizados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('info', 'Curso eliminada');
    }
}
