<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

class MisMenoresController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;
        
        // Traemos a sus hijos y el nombre de su clase/curso
        $misHijos = $tutor->alumnos()->with(['clase', 'curso'])->get();

        return view('familia.hijos.index', compact('misHijos'));
    }

    public function show($id)
    {
        $tutor = Auth::user()->tutor;

        // SEGURIDAD: Solo puede ver el perfil si el alumno es su hijo
        $hijo = $tutor->alumnos()
            ->with(['clase.docentes.user', 'ausencias'])
            ->findOrFail($id);

        return view('familia.hijos.show', compact('hijo'));
    }
}