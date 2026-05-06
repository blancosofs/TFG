<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'registros'              => 'required|array',
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
                // Borramos las ausencias previas del mismo día y clase para evitar duplicados
                Ausencia::where('fecha', $request->fecha)
                    ->where('docente_id', $docente->id)
                    ->whereIn('alumno_id', collect($request->registros)->pluck('alumno_id'))
                    ->delete();

                foreach ($request->registros as $reg) {
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
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve las ausencias de un alumno para el panel del tutor.
     */
    public function porAlumno($alumnoId)
    {
        $ausencias = Ausencia::where('alumno_id', $alumnoId)
            ->orderBy('fecha', 'desc')
            ->get(['id', 'fecha', 'tipo', 'justificada', 'justificacion']);

        return response()->json($ausencias);
    }


    public function update(Request $request, Ausencia $ausencia)
    {
        $ausencia->update($request->only(['justificada', 'justificacion']));
        return response()->json(['ok' => true, 'mensaje' => 'Ausencia actualizada']);
    }

    public function destroy(Ausencia $ausencia)
    {
        $ausencia->delete();
        return response()->json(['ok' => true, 'mensaje' => 'Ausencia eliminada']);
    }
}
