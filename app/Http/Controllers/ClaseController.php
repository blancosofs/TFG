<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Clase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaseController extends Controller
{
    /**
     * Devuelve las clases asignadas al docente autenticado.
     */
    public function misClases()
    {
        $docente = Auth::user()->docente;
        if (!$docente) {
            return response()->json([]);
        }

        $asignaturas = $docente->asignaturas
            ? array_values(array_filter(array_map('trim', explode(',', $docente->asignaturas))))
            : [];

        $clases = $docente->clases()->with('curso')->get()->map(fn($clase) => [
            'id'          => $clase->id,
            'nombre'      => $clase->nombre,
            'curso'       => $clase->curso ? $clase->curso->nombre : '—',
            'asignaturas' => $asignaturas,
        ]);

        return response()->json($clases);
    }

    /**
     * Devuelve los alumnos de una clase (para pasar lista).
     */
    public function visualizarAlumnos($id)
    {
        $colegioId = Auth::user()->colegio_id;
        $clase = Clase::whereHas('curso', fn($q) => $q->where('colegio_id', $colegioId))
            ->findOrFail($id);

        $alumnos = Alumno::where('clase_id', $clase->id)
            ->where('activo', true)
            ->orderBy('apellidos')
            ->get(['id', 'nombre', 'apellidos']);

        return response()->json($alumnos);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Modo calendario: expande horarios del docente en eventos con fecha concreta
        if ($request->has('desde') && $request->has('hasta')) {
            $docente = Auth::user()->docente;
            if (!$docente) return response()->json([]);

            $desde = Carbon::parse($request->desde);
            $hasta = Carbon::parse($request->hasta);

            $horarios = $docente->horarios()->with('clase.curso')->get();

            $diasMap = [
                'lunes' => 1, 'martes' => 2, 'miercoles' => 3,
                'jueves' => 4, 'viernes' => 5,
            ];

            $eventos = [];
            $current = $desde->copy();

            while ($current->lte($hasta)) {
                if ($current->dayOfWeekIso <= 5) {
                    foreach ($horarios as $h) {
                        if (($diasMap[$h->dia_semana] ?? null) === $current->dayOfWeekIso) {
                            $eventos[] = [
                                'fecha'       => $current->format('Y-m-d'),
                                'hora_inicio' => $h->getRawOriginal('hora_inicio'),
                                'hora_fin'    => $h->getRawOriginal('hora_fin'),
                                'materia'     => $h->clase?->nombre ?? '—',
                                'grupo'       => $h->clase?->curso?->nombre ?? '—',
                                'aula'        => null,
                                'clase_id'    => $h->clase_id,
                            ];
                        }
                    }
                }
                $current->addDay();
            }

            return response()->json($eventos);
        }

        if ($request->has('curso_id')) {
            $clases = Clase::where('curso_id', $request->curso_id)->get();
            return response()->json($clases);
        }

        $colegioId = Auth::user()->colegio_id;
        $clases = Clase::whereHas('curso', function ($query) use ($colegioId) {
            $query->where('colegio_id', $colegioId);
        })->get();

        return response()->json($clases);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string|max:10',
        'codigo_acceso' => 'nullable|string|max:10',
        'curso_id' => 'required|integer|exists:cursos,id',
    ]);

    // Si todo está bien, lo guardamos
        Clase::create($request->all());

        return response()->json([
            'ok' => true,
            'mensaje' => 'Clase creada con éxito'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $clase = Clase::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:10',
            'codigo_acceso' => 'nullable|string|max:10',
            'curso_id' => 'required|integer|exists:cursos,id',
        ]);

        $clase->update($request->all());
        return response()->json([
            'ok' => true,
            'mensaje' => 'Clase actualizada con éxito'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clase = Clase::findOrFail($id);

        $clase->delete();
        
        return response()->json([
            'ok' => true,
            'mensaje' => 'Clase eliminada con éxito'
        ]);
    }
}
