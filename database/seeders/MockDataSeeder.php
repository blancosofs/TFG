<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Colegio;
use App\Models\User;
use App\Models\Coordinador;
use App\Models\Curso;
use App\Models\Clase;
use App\Models\Docente;
use App\Models\Alumno;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos un Colegio
        $colegio = Colegio::create([
            'nombre' => 'Colegio San Patricio',
            'entidad' => 'Privada',
            'direccion' => 'Calle Falsa 123, Madrid',
            'activo' => true
        ]);

        // 2. Creamos al Usuario que será el Coordinador
        $userCoordinador = User::create([
            'name' => 'Carlos',
            'apellidos' => 'García (Coord)',
            'email' => 'coordinador@sanpatricio.com',
            'password' => Hash::make('12345678'),
            'colegio_id' => $colegio->id
        ]);

        // Lo registramos como coordinador del colegio
        $coordinador = Coordinador::create([
            'colegio_id' => $colegio->id,
            'user_id' => $userCoordinador->id
        ]);

        // 3. Creamos un Curso
        $curso = Curso::create([
            'nombre' => '1º ESO',
            'colegio_id' => $colegio->id
        ]);

        // 4. Creamos una Clase para ese Curso
        $clase = Clase::create([
            'nombre' => '1ºA',
            'codigo_acceso' => '1ESOA-2026',
            'curso_id' => $curso->id
        ]);

        // 5. Creamos un Usuario que será Docente
        $userDocente = User::create([
            'name' => 'Laura',
            'apellidos' => 'Martínez (Docente)',
            'email' => 'laura@sanpatricio.com',
            'password' => Hash::make('12345678'),
            'colegio_id' => $colegio->id
        ]);

        // Lo registramos en la tabla docentes
        $docente = Docente::create([
            'telefono' => '600123456',
            'colegio_id' => $colegio->id,
            'coordinador_id' => $coordinador->id,
            'user_id' => $userDocente->id
        ]);

        // 6. Creamos un par de Alumnos
        Alumno::create([
            'nombre' => 'Hugo',
            'apellidos' => 'López',
            'colegio_id' => $colegio->id,
            'curso_id' => $curso->id,
            'clase_id' => $clase->id,
            'activo' => true
        ]);

        Alumno::create([
            'nombre' => 'Sofía',
            'apellidos' => 'Pérez',
            'colegio_id' => $colegio->id,
            'curso_id' => $curso->id,
            'clase_id' => $clase->id,
            'activo' => true
        ]);
    }
}