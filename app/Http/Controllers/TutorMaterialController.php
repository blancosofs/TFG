<?php

namespace App\Http\Controllers;

use App\Models\MaterialRepaso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorMaterialController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;
        $materiales = MaterialRepaso::whereHas('tutores', fn($q) => $q->where('tutores.id', $tutor->id))
            ->publicados()
            ->with('docente.user')
            ->ordenadasPorFecha()
            ->paginate(15);

        return view('tutor.materiales-index', compact('materiales'));
    }

    public function show(MaterialRepaso $materialRepaso)
    {
        $tutor = Auth::user()->tutor;
        $tieneAcceso = $materialRepaso->tutores()->where('tutores.id', $tutor->id)->exists();
        if (!$tieneAcceso) abort(403, 'No tienes acceso a este material.');

        $materialRepaso->load('docente.user');
        return view('tutor.materiales-show', ['material' => $materialRepaso]);
    }

    public function descargar(MaterialRepaso $materialRepaso)
    {
        $tutor = Auth::user()->tutor;
        $tieneAcceso = $materialRepaso->tutores()->where('tutores.id', $tutor->id)->exists();
        if (!$tieneAcceso) abort(403);

        if (!$materialRepaso->archivo_ruta) abort(404, 'Archivo no disponible.');

        return response()->download(
            Storage::disk('private')->path($materialRepaso->archivo_ruta),
            $materialRepaso->archivo_nombre_original
        );
    }
}
