<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
{
public function index()
    {
        $colegioId = Auth::user()->colegio_id;

        $horarios = Horario::with(['docente.user', 'clase'])
            ->whereHas('docente', fn($q) => $q->where('colegio_id', $colegioId))
            ->get()
            ->map(fn($h) => [
                'id'          => $h->id,
                'docente_id'  => $h->docente_id,
                'clase_id'    => $h->clase_id,
                'dia_semana'  => $h->dia_semana,
                'hora_inicio' => $h->hora_inicio,
                'hora_fin'    => $h->hora_fin,
                'asignatura'  => $h->asignatura,
                'docente'     => $h->docente ? trim("{$h->docente->user->name} {$h->docente->user->apellidos}") : '—',
                'clase'       => $h->clase?->nombre ?? '—',
            ]);

        return response()->json($horarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'dia_semana'  => 'required|in:lunes,martes,miercoles,jueves,viernes',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'docente_id'  => 'required|integer|exists:docentes,id',
            'clase_id'    => 'required|integer|exists:clases,id',
            'asignatura'  => 'nullable|string|max:100',
        ]);

        $horario = Horario::create($datosValidados);

        // Sincronizar tabla pivote para que el docente vea la clase en "pasar lista"
        Docente::find($horario->docente_id)
            ->clases()
            ->syncWithoutDetaching([$horario->clase_id]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario creado con éxito',
            'horario' => $horario,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        // Ponemos 'sometimes' para permitir actualizaciones parciales
        $datosValidados = $request->validate([
            'dia_semana'  => 'sometimes|required|in:lunes,martes,miercoles,jueves,viernes',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin'    => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'docente_id'  => 'sometimes|required|integer|exists:docentes,id',
            'clase_id'    => 'sometimes|required|integer|exists:clases,id',
            'asignatura'  => 'nullable|string|max:100',
        ]);

        $oldDocenteId = $horario->docente_id;
        $oldClaseId   = $horario->clase_id;

        $horario->update($datosValidados);

        // Adjuntar nueva combinación docente+clase a la pivote
        Docente::find($horario->docente_id)
            ->clases()
            ->syncWithoutDetaching([$horario->clase_id]);

        // Si cambió el docente o la clase, limpiar la combinación anterior si ya no tiene horarios
        if ($oldDocenteId !== $horario->docente_id || $oldClaseId !== $horario->clase_id) {
            $sigueExistiendo = Horario::where('docente_id', $oldDocenteId)
                ->where('clase_id', $oldClaseId)
                ->exists();
            if (!$sigueExistiendo) {
                Docente::find($oldDocenteId)->clases()->detach($oldClaseId);
            }
        }

        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario actualizado con éxito',
            'horario' => $horario,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $docenteId = $horario->docente_id;
        $claseId   = $horario->clase_id;

        $horario->delete();

        // Si ya no quedan horarios para esta combinación, quitar la clase de la pivote
        $sigueExistiendo = Horario::where('docente_id', $docenteId)
            ->where('clase_id', $claseId)
            ->exists();
        if (!$sigueExistiendo) {
            Docente::find($docenteId)->clases()->detach($claseId);
        }

        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario eliminado con éxito',
        ]);
    }

}    