<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AusenciaController extends Controller
{
    public function index()
    {
        $ausencias = Ausencia::all();
        return view('ausencias.index', compact('ausencias'));
    }


    public function storeAusencia(Request $request)
    {
        $request->validate([
            'fecha'         => 'required|date',
            'tipo'          => 'required|in:falta,retraso',
            'justificada'   => 'required|boolean',
            'justificacion' => 'nullable|string|max:255',
            'alumno_id'     => 'required|integer|exists:alumnos,id',
            'docente_id'    => 'required|integer|exists:docentes,id',
            'horario_id'    => 'nullable|integer|exists:horarios,id',
        ]);

        Ausencia::create($request->all());

        return response()->
        json(['ok' => true, 'mensaje' => 'Ausencia registrada']);
    }

    /**
     * Guarda el registro de asistencia completo desde el frontend del docente.
     * Recibe un array de alumnos no presentes (ausentes o retrasos).
     */
    public function storeAsistencia(Request $request)
    {
        $request->validate([
            'fecha'                  => 'required|date',
            'clase_id'               => 'required|integer|exists:clases,id',
            'registros'              => 'nullable|array',
            'registros.*.alumno_id'  => 'required|integer|exists:alumnos,id',
            'registros.*.estado'     => 'required|in:ausente,retraso',
            'registros.*.nota'       => 'nullable|string|max:500',
        ]);

        $docente = Auth::user()->docente;
        if (!$docente) {
            return response()->json(['ok' => false, 'mensaje' => 'No tienes perfil de docente.'], 403);
        }

        try {
            DB::transaction(function () use ($request, $docente) {
                // Borrar TODAS las ausencias previas de esta clase/fecha para evitar duplicados
                $alumnoIds = \App\Models\Alumno::where('clase_id', $request->clase_id)->pluck('id');
                Ausencia::where('fecha', $request->fecha)
                    ->where('docente_id', $docente->id)
                    ->whereIn('alumno_id', $alumnoIds)
                    ->delete();

                foreach ($request->registros ?? [] as $reg) {
                    Ausencia::create([
                        'fecha'         => $request->fecha,
                        'tipo'          => $reg['estado'] === 'retraso' ? 'retraso' : 'falta',
                        'justificada'   => false,
                        'justificacion' => $reg['nota'] ?? null,
                        'alumno_id'     => $reg['alumno_id'],
                        'docente_id'    => $docente->id,
                        'horario_id'    => null,
                    ]);
                }
            });

            return response()->json(['ok' => true, 'mensaje' => 'Asistencia registrada correctamente.']);

        } catch (\Exception $e) {
            Log::error('AusenciaController@storeAsistencia: ' . $e->getMessage());
            return response()->json(['ok' => false, 'mensaje' => 'No se pudo registrar la asistencia. Inténtalo de nuevo.'], 500);
        }
    }

    /**
     * Devuelve las ausencias de un alumno para el panel del tutor.
     */
    public function porAlumno($alumnoId)
    {
        $tutor = Auth::user()->tutor;
        if (!$tutor) return response()->json([], 403);

        $tieneAcceso = $tutor->alumnos()->where('alumnos.id', $alumnoId)->exists();
        if (!$tieneAcceso) return response()->json([], 403);

        $ausencias = Ausencia::where('alumno_id', $alumnoId)
            ->orderBy('fecha', 'desc')
            ->get(['id', 'fecha', 'tipo', 'justificada', 'justificacion']);

        return response()->json($ausencias);
    }


    public function porClase(Request $request, $claseId)
    {
        $docente = Auth::user()->docente;
        if (!$docente) return response()->json([], 403);

        $alumnoIds = \App\Models\Alumno::where('clase_id', $claseId)->pluck('id');

        return response()->json(
            Ausencia::whereIn('alumno_id', $alumnoIds)
                ->orderBy('fecha', 'desc')
                ->get(['id', 'alumno_id', 'fecha', 'tipo', 'justificada', 'justificacion'])
        );
    }

    public function update(Request $request, Ausencia $ausencia)
    {
        $tutor = Auth::user()->tutor;
        if ($tutor) {
            $tieneAcceso = $tutor->alumnos()->where('alumnos.id', $ausencia->alumno_id)->exists();
            if (!$tieneAcceso) return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

        $ausencia->update($request->only(['justificada', 'justificacion']));
        return response()->json(['ok' => true, 'mensaje' => 'Ausencia actualizada']);
    }

    public function destroy(Ausencia $ausencia)
    {
        $ausencia->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Ausencia eliminada']);
    }
}
