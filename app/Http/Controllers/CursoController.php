<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
 
    public function index() {
        // Devuelve todos los cursos (para printear en la pagina) <-- Pendiente
        $cursos = Curso::all();

    }

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
}
