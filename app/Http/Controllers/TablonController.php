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

    public function apiIndex()
    {
        $user      = Auth::user();
        $colegioId = $user->colegio_id;

        $query = Tablon::with(['user', 'docente.user', 'clase', 'comentarios.user.docente', 'comentarios.user.tutor'])
            ->where(function ($q) use ($colegioId) {
                // Publicaciones de docentes del mismo colegio
                $q->whereHas('docente', fn($q2) => $q2->where('colegio_id', $colegioId))
                // Publicaciones de coordinadores del mismo colegio (sin docente_id)
                  ->orWhere(function ($q2) use ($colegioId) {
                      $q2->whereNull('docente_id')
                         ->whereHas('user', fn($q3) => $q3->where('colegio_id', $colegioId));
                  });
            })
            ->orderBy('created_at', 'desc');

        if ($user->tutor) {
            $query->where('dirigido_a', '!=', 'Solo docentes');
        }

        $publicaciones = $query->get()->map(fn($p) => [
            'id'              => $p->id,
            'titulo'          => $p->titulo,
            'contenido'       => $p->contenido,
            'categoria'       => $p->categoria,
            'dirigido_a'      => $p->dirigido_a,
            'fecha_limite'    => $p->fecha_limite,
            'created_at'      => $p->created_at,
            // user_id del creador (sirve para saber si el usuario actual es autor)
            'autor_user_id'   => $p->user_id,
            // Para retrocompatibilidad con el JS existente
            'docente_user_id' => $p->docente?->user_id ?? $p->user_id,
            'docente'         => $p->docente ? [
                'user' => ['name' => $p->docente->user->name, 'apellidos' => $p->docente->user->apellidos ?? ''],
            ] : ($p->user ? [
                'user' => ['name' => $p->user->name, 'apellidos' => $p->user->apellidos ?? ''],
            ] : null),
            'clase'           => $p->clase ? ['id' => $p->clase->id, 'nombre' => $p->clase->nombre] : null,
            'comentarios'     => $p->comentarios->map(fn($c) => [
                'id'      => $c->id,
                'user_id' => $c->user_id,
                'autor'   => $c->user ? trim("{$c->user->name} {$c->user->apellidos}") : 'Usuario',
                'rol'     => $c->user?->docente ? 'docente' : ($c->user?->tutor ? 'tutor' : 'coordinador'),
                'texto'   => $c->texto,
                'fecha'   => $c->created_at,
            ]),
        ]);

        return response()->json($publicaciones);
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

        $user        = Auth::user();
        $docente     = $user->docente;
        $coordinador = $user->coordinador;

        if (!$docente && !$coordinador) {
            return response()->json(['ok' => false, 'mensaje' => 'Solo docentes y coordinadores pueden publicar.'], 403);
        }

        $tablon = Tablon::create([
            'user_id'     => $user->id,
            'docente_id'  => $docente?->id,
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
        $user    = Auth::user();
        $esAutor = $tablon->user_id === $user->id
                || ($user->docente && $tablon->docente_id === $user->docente->id);

        if (!$esAutor) {
            return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

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
        $user          = Auth::user();
        $esAutor       = $tablon->user_id === $user->id
                      || ($user->docente && $tablon->docente_id === $user->docente->id);
        $esCoordinador = (bool) $user->coordinador;

        if (!$esAutor && !$esCoordinador) {
            return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

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
        $user = Auth::user();
        $esAutor = $comentario->user_id === $user->id;
        $esCoordinador = (bool) $user->coordinador;

        if (!$esAutor && !$esCoordinador) {
            return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

        $comentario->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Comentario eliminado.']);
    }
}
