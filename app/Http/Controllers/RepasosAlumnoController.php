<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

class MaterialRepasoController extends Controller
{
    public function create()
    {
        // Solo dejamos elegir entre los hijos del tutor logueado
        $hijos = Auth::user()->tutor->alumnos;
        return view('familia.material.create', compact('hijos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'    => 'required|string|max:100',
            'link'      => 'required|url',
            'alumno_id' => 'required|exists:alumnos,id'
        ]);

        return redirect()->route('familia.mis-hijos.index')
            ->with('success', 'Material de repaso compartido con éxito.');
    }
}

?>