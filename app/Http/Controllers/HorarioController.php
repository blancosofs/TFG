<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
public function index()
    {
        $horarios = Horario::with(['docente', 'clase'])->get();
        
        return response()->json([
            'ok' => true,
            'horarios' => $horarios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'docente_id' => 'required|integer|exists:docentes,id',
            'clase_id' => 'required|integer|exists:clases,id'
        ]);

        // Guardamos usando solo los datos que han pasado la validación
        $horario = Horario::create($datosValidados);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario creado con éxito',
            'horario' => $horario // Devolvemos el objeto recién creado
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        // Ponemos 'sometimes' para permitir actualizaciones parciales
        $datosValidados = $request->validate([
            'dia_semana' => 'sometimes|required|in:lunes,martes,miercoles,jueves,viernes',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin' => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'docente_id' => 'sometimes|required|integer|exists:docentes,id',
            'clase_id' => 'sometimes|required|integer|exists:clases,id'
        ]);

        // Actualizamos de forma segura
        $horario->update($datosValidados);

        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario actualizado con éxito',
            'horario' => $horario
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();
        
        return response()->json([
            'ok' => true,
            'mensaje' => 'Horario eliminado con éxito'
        ]);
    }

}    