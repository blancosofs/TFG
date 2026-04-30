<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use Illuminate\Support\Facades\Auth;

class MisClasesController extends Controller
{
    public function index()
    {
        // 1. Obtenemos al docente logueado
        $docente = Auth::user()->docente;

        // 2. Traemos sus clases. Usamos withCount para saber cuántos alumnos tiene cada una
        $misClases = $docente->clases()->withCount('alumnos')->get();

        return view('profesor.mis_clases.index', compact('misClases'));
    }

    public function show($id)
    {
        $docente = Auth::user()->docente;

        // 3. SEGURIDAD: Buscamos la clase pero solo si pertenece a este docente
        $clase = $docente->clases()->with('alumnos')->findOrFail($id);

        return view('profesor.mis_clases.show', compact('clase'));
    }
}