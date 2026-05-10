<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colegioId = Auth::user()->colegio_id;
        $cursos = Curso::where('colegio_id', $colegioId)->get();

        return response()->json($cursos);
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request) {
        $request->validate(['nombre' => 'required|string|max:30']);

        $curso = Curso::create([
            'nombre'     => $request->nombre,
            'colegio_id' => Auth::user()->colegio_id,
        ]);

        return response()->json(['ok' => true, 'mensaje' => 'Curso creado con éxito', 'curso' => $curso], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {       
        $request->validate(['nombre' => 'required|string|max:30']);

        $curso->update(['nombre' => $request->nombre]);

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Datos del curso actualizados',
            'curso' => $curso
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Curso eliminado'] );
    }
}
