<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Colegio;
use App\Models\User;
use App\Models\Coordinador;
use App\Models\Curso;
use App\Models\Clase;
use App\Models\Docente;
use App\Models\Tutor;
use App\Models\Alumno;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Contraseña común para facilitar las pruebas
        $password = Hash::make('demo1234');

// 0. ADMIN
User::updateOrCreate(['email' => 'admin@demo.com'], [
    'name' => 'Admin Demo', 'password' => $password, 'activo' => true
]);

        // 1. COLEGIO
        $colegio = Colegio::firstOrCreate(
            ['nombre' => 'Colegio Demo'],
            ['entidad' => 'Privada', 'direccion' => 'Calle Falsa 123', 'activo' => true]
        );

       // 2. COORDINADOR
$userCoord = User::updateOrCreate(['email' => 'coordinador@demo.com'], [
    'name' => 'Coord Demo', 'password' => $password, 'colegio_id' => $colegio->id, 'activo' => true
]);

        $coordinador = Coordinador::firstOrCreate(
            ['user_id' => $userCoord->id],
            ['colegio_id' => $colegio->id]
        );

        // 3. CURSO
        $curso = Curso::firstOrCreate(
            ['nombre' => '1º ESO Demo'],
            ['colegio_id' => $colegio->id]
        );

        // 4. CLASE 
        $clase = Clase::firstOrCreate(
            ['nombre' => '1ºA Demo'],
            ['codigo_acceso' => 'DEMO-2026', 'curso_id' => $curso->id]
        );

        // 5. DOCENTE 
$userDocente = User::updateOrCreate(['email' => 'docente@demo.com'], [
    'name' => 'Docente Demo', 'password' => $password, 'colegio_id' => $colegio->id, 'activo' => true
]);
        Docente::firstOrCreate(
            ['user_id' => $userDocente->id],
            ['telefono' => '600000001', 'colegio_id' => $colegio->id, 'coordinador_id' => $coordinador->id]
        );

        // 6. TUTOR (Familia)
       // 6. TUTOR (Familia)
$userFamilia = User::updateOrCreate(['email' => 'familia@demo.com'], [
    'name' => 'Familia Demo', 'password' => $password, 'colegio_id' => $colegio->id, 'activo' => true
]);
        $tutor = Tutor::firstOrCreate(
            ['user_id' => $userFamilia->id],
            ['telefono' => '600000002']
        );

        // 7. ALUMNO
        $alumno = Alumno::firstOrCreate(
            ['nombre' => 'Alumno Demo', 'apellidos' => 'Pruebas'],
            ['colegio_id' => $colegio->id, 'curso_id' => $curso->id, 'clase_id' => $clase->id, 'activo' => true]
        );

        // 8. RELACIÓN TUTOR-ALUMNO (Pivot)
        DB::table('tutores_alumnos')->updateOrInsert(
            ['tutor_id' => $tutor->id, 'alumno_id' => $alumno->id],
            ['parentesco' => 'Padre/Madre']
        );

        
    }
}