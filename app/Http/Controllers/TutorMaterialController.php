<?php

namespace App\Http\Controllers;

use App\Models\MaterialRepaso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorMaterialController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
       VISTAS SHELL
       Devuelven la página vacía. El JS carga los datos via API.
    ═══════════════════════════════════════════════════════════ */

    // Lista de materiales disponibles para el tutor (shell sin datos)
    public function index()
    {
        return view('tutor.materiales-index');
    }

    // Detalle de un material: pasa solo el ID al JS para que pida los datos
    public function show(MaterialRepaso $materialRepaso)
    {
        $tutor       = Auth::user()->tutor;
        $tieneAcceso = $materialRepaso->tutores()->where('tutores.id', $tutor->id)->exists();
        if (!$tieneAcceso) abort(403, 'No tienes acceso a este material.');
        return view('tutor.materiales-show', ['id' => $materialRepaso->id]);
    }

    // Descarga directa del archivo (no cambia; sigue siendo una respuesta de fichero)
    public function descargar(MaterialRepaso $materialRepaso)
    {
        $tutor       = Auth::user()->tutor;
        $tieneAcceso = $materialRepaso->tutores()->where('tutores.id', $tutor->id)->exists();
        if (!$tieneAcceso) abort(403);

        if (!$materialRepaso->archivo_ruta) abort(404, 'Archivo no disponible.');

        return response()->download(
            Storage::disk('private')->path($materialRepaso->archivo_ruta),
            $materialRepaso->archivo_nombre_original
        );
    }

    /* ═══════════════════════════════════════════════════════════
       RUTAS JSON — LECTURA
       Llamadas desde JS con fetch. Devuelven JSON.
    ═══════════════════════════════════════════════════════════ */

    // Devuelve la lista paginada de materiales publicados accesibles al tutor
    public function listar()
    {
        $tutor      = Auth::user()->tutor;
        $materiales = MaterialRepaso::whereHas('tutores', fn($q) => $q->where('tutores.id', $tutor->id))
            ->publicados()
            ->with('docente.user')
            ->ordenadasPorFecha()
            ->paginate(15);

        return response()->json([
            'data' => $materiales->map(fn($m) => [
                'id'                      => $m->id,
                'titulo'                  => $m->titulo,
                'descripcion'             => $m->descripcion,
                'tipo_contenido'          => $m->tipo_contenido,
                'archivo_nombre_original' => $m->archivo_nombre_original,
                'tamano_legible'          => $m->tamañoLegible,
                'url_externa'             => $m->url_externa,
                'materia'                 => $m->materia,
                'tema'                    => $m->tema,
                'docente'                 => $m->docente->user->name . ' ' . $m->docente->user->apellidos,
                'created_at'              => $m->created_at->format('d/m/Y'),
            ])->values(),
            'meta' => [
                'current_page' => $materiales->currentPage(),
                'last_page'    => $materiales->lastPage(),
                'total'        => $materiales->total(),
            ],
        ]);
    }

    // Devuelve el detalle de un material concreto (verifica que el tutor tenga acceso)
    public function detallar(MaterialRepaso $materialRepaso)
    {
        $tutor       = Auth::user()->tutor;
        $tieneAcceso = $materialRepaso->tutores()->where('tutores.id', $tutor->id)->exists();
        if (!$tieneAcceso) abort(403);

        $materialRepaso->load('docente.user');

        return response()->json([
            'id'                      => $materialRepaso->id,
            'titulo'                  => $materialRepaso->titulo,
            'descripcion'             => $materialRepaso->descripcion,
            'tipo_contenido'          => $materialRepaso->tipo_contenido,
            'archivo_nombre_original' => $materialRepaso->archivo_nombre_original,
            'tamano_legible'          => $materialRepaso->tamañoLegible,
            'url_externa'             => $materialRepaso->url_externa,
            'materia'                 => $materialRepaso->materia,
            'tema'                    => $materialRepaso->tema,
            'docente'                 => $materialRepaso->docente->user->name . ' ' . $materialRepaso->docente->user->apellidos,
            'created_at'              => $materialRepaso->created_at->format('d/m/Y'),
        ]);
    }
}
