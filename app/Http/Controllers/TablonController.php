<?php

namespace App\Http\Controllers;

use App\Models\Tablon;
use App\Models\ComentarioTablon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TablonController extends Controller
{
    public function index()
    {
        $publicaciones = Tablon::with(['docente', 'tutor', 'clase'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tablon.index', compact('publicaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:200',
            'categoria'   => 'required|in:General,Examen,Evento,Urgente,Tarea',
            'dirigido_a'  => 'required|in:Todos,Solo familias,Solo docentes',
            'contenido'   => 'required|string|max:2000',
            'clase_id'    => 'nullable|integer|exists:clases,id',
            'fecha_limite'=> 'nullable|date',
        ]);

        $docente = Auth::user()->docente;
        if (!$docente) {
            return response()->json(['ok' => false, 'mensaje' => 'No tienes perfil de docente.'], 403);
        }

        $tablon = Tablon::create([
            'docente_id'  => $docente->id,
            'tutor_id'    => Auth::user()->tutor?->id,
            'titulo'      => $request->titulo,
            'categoria'   => $request->categoria,
            'dirigido_a'  => $request->dirigido_a,
            'contenido'   => $request->contenido,
            'clase_id'    => $request->clase_id,
            'fecha_limite'=> $request->fecha_limite,
        ]);

        return response()->json(['ok' => true, 'tablon' => $tablon]);
    }

    public function show(Tablon $tablon)
    {
        $tablon->load(['docente', 'tutor', 'clase', 'comentarios.user']);
        return view('tablon.show', compact('tablon'));
    }

    public function update(Request $request, Tablon $tablon)
    {
        $request->validate([
            'titulo'      => 'sometimes|string|max:200',
            'categoria'   => 'sometimes|in:General,Examen,Evento,Urgente,Tarea',
            'dirigido_a'  => 'sometimes|in:Todos,Solo familias,Solo docentes',
            'contenido'   => 'sometimes|string|max:2000',
            'clase_id'    => 'nullable|integer|exists:clases,id',
            'fecha_limite'=> 'nullable|date',
        ]);

        $tablon->update($request->only([
            'titulo', 'categoria', 'dirigido_a', 'contenido', 'clase_id', 'fecha_limite',
        ]));

        return response()->json(['ok' => true, 'tablon' => $tablon]);
    }

    public function destroy(Tablon $tablon)
    {
        $tablon->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Publicación eliminada.']);
    }

    // --- Comentarios ---

    public function storeComentario(Request $request, Tablon $tablon)
    {
        $request->validate([
            'texto' => 'required|string|max:1000',
        ]);

        $comentario = ComentarioTablon::create([
            'tablon_id' => $tablon->id,
            'user_id'   => Auth::id(),
            'texto'     => $request->texto,
        ]);

        $comentario->load('user');

        return response()->json(['ok' => true, 'comentario' => $comentario]);
    }

    public function destroyComentario(ComentarioTablon $comentario)
    {
        $comentario->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Comentario eliminado.']);
    }
}
