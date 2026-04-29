<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Ausencia;

class DashboardController extends Controller
{
    /**
     * Pantalla principal del Coordinador (Resumen general)
     */
    public function index()
    {
        // 1. Obtenemos al coordinador que ha iniciado sesión
        $user = Auth::user();
        $colegioId = $user->colegio_id;

        // 2. Sacamos las estadísticas rápidas (usando ->count() para no sobrecargar la memoria)
        $totalAlumnos = Alumno::where('colegio_id', $colegioId)->where('activo', true)->count();
        $totalDocentes = Docente::where('colegio_id', $colegioId)->count();
        $totalCursos = Curso::where('colegio_id', $colegioId)->count();

        // 3. Faltas registradas el día de hoy en su colegio
        // Usamos whereHas para buscar solo ausencias de alumnos que pertenezcan a SU colegio
        $faltasHoy = Ausencia::whereDate('fecha', today())
            ->whereHas('alumno', function ($query) use ($colegioId) {
                $query->where('colegio_id', $colegioId);
            })->count();

        // 4. Mandamos todo este resumen a la vista
        return view('coordinacion.dashboard.index', compact(
            'totalAlumnos', 
            'totalDocentes', 
            'totalCursos', 
            'faltasHoy'
        ));
    }

    /**
     * Pantalla específica de Estadísticas de Asistencia
     */
    public function estadisticasAsistencia()
    {
        $colegioId = Auth::user()->colegio_id;

        // Aquí podríamos sacar las ausencias del último mes para hacer una gráfica
        $ausenciasMes = Ausencia::whereMonth('fecha', date('m'))
            ->whereYear('fecha', date('Y'))
            ->whereHas('alumno', function ($query) use ($colegioId) {
                $query->where('colegio_id', $colegioId);
            })->get();

        return view('coordinacion.dashboard.asistencia', compact('ausenciasMes'));
    }

    /**
     * Pantalla de Reportes de Excursiones (Por ahora un esqueleto)
     */
    public function reporteExcursiones()
    {
        // Lógica futura para excursiones
        return view('coordinacion.dashboard.excursiones');
    }
}