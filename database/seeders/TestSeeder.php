<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = [];
        $now = now();

        // ─────────────────────────────────────────
        // COLEGIO
        // ─────────────────────────────────────────
        $colegioId = DB::table('colegios')->insertGetId([
            'nombre'      => 'Colegio Test San Isidro',
            'entidad'     => 'Entidad Educativa Test',
            'direccion'   => 'Calle Ficticia 123',
            'ciudad'      => 'Madrid',
            'comunidad'   => 'Comunidad de Madrid',
            'cp'          => '28001',
            'telefono'    => '910000000',
            'email'       => 'colegio@test.com',
            'tipo'        => 'Concertado',
            'etapas'      => 'Primaria',
            'activo'      => true,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // ─────────────────────────────────────────
        // CURSOS Y CLASES
        // ─────────────────────────────────────────
        $curso1Id = DB::table('cursos')->insertGetId([
            'nombre'     => '1º Primaria',
            'colegio_id' => $colegioId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $curso2Id = DB::table('cursos')->insertGetId([
            'nombre'     => '2º Primaria',
            'colegio_id' => $colegioId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $clase1Id = DB::table('clases')->insertGetId([
            'nombre'         => '1ºA',
            'codigo_acceso'  => 'TEST1A',
            'curso_id'       => $curso1Id,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        $clase2Id = DB::table('clases')->insertGetId([
            'nombre'         => '2ºA',
            'codigo_acceso'  => 'TEST2A',
            'curso_id'       => $curso2Id,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        // ─────────────────────────────────────────
        // ADMIN  (usuario sin tabla de rol asociada)
        // ─────────────────────────────────────────
        $adminPass = 'admin1234';
        DB::table('users')->insertGetId([
            'name'              => 'Admin',
            'apellidos'         => 'Test',
            'email'             => 'admin@test.com',
            'email_verified_at' => $now,
            'password'          => Hash::make($adminPass),
            'colegio_id'        => $colegioId,
            'activo'            => true,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        $credentials[] = ['rol' => 'Admin        ', 'email' => 'admin@test.com', 'password' => $adminPass];

        // ─────────────────────────────────────────
        // COORDINADOR
        // ─────────────────────────────────────────
        $coordPass = 'coord1234';
        $coordUserId = DB::table('users')->insertGetId([
            'name'              => 'Carmen',
            'apellidos'         => 'López Test',
            'email'             => 'coordinador@test.com',
            'email_verified_at' => $now,
            'password'          => Hash::make($coordPass),
            'colegio_id'        => $colegioId,
            'activo'            => true,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        $coordinadorId = DB::table('coordinadores')->insertGetId([
            'colegio_id' => $colegioId,
            'user_id'    => $coordUserId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $credentials[] = ['rol' => 'Coordinador  ', 'email' => 'coordinador@test.com', 'password' => $coordPass];

        // ─────────────────────────────────────────
        // DOCENTES
        // ─────────────────────────────────────────
        $docentes = [
            [
                'name'       => 'Pedro',
                'apellidos'  => 'Martínez Test',
                'email'      => 'docente1@test.com',
                'telefono'   => '611000001',
                'asignaturas'=> 'Matemáticas, Ciencias',
                'clase_id'   => $clase1Id,
            ],
            [
                'name'       => 'Laura',
                'apellidos'  => 'García Test',
                'email'      => 'docente2@test.com',
                'telefono'   => '611000002',
                'asignaturas'=> 'Lengua, Inglés',
                'clase_id'   => $clase2Id,
            ],
        ];

        $docenteIds = [];
        foreach ($docentes as $i => $d) {
            $pass = 'docente' . ($i + 1) . '1234';
            $userId = DB::table('users')->insertGetId([
                'name'              => $d['name'],
                'apellidos'         => $d['apellidos'],
                'email'             => $d['email'],
                'email_verified_at' => $now,
                'password'          => Hash::make($pass),
                'colegio_id'        => $colegioId,
                'activo'            => true,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            $docenteId = DB::table('docentes')->insertGetId([
                'telefono'       => $d['telefono'],
                'asignaturas'    => $d['asignaturas'],
                'colegio_id'     => $colegioId,
                'coordinador_id' => $coordinadorId,
                'user_id'        => $userId,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            // Asignar docente a su clase
            DB::table('docentes_clases')->insert([
                'docente_id' => $docenteId,
                'clase_id'   => $d['clase_id'],
            ]);
            // Horario de ejemplo (lunes 9:00–10:00)
            DB::table('horarios')->insert([
                'dia_semana'  => 'lunes',
                'hora_inicio' => '09:00:00',
                'hora_fin'    => '10:00:00',
                'asignatura'  => explode(',', $d['asignaturas'])[0],
                'docente_id'  => $docenteId,
                'clase_id'    => $d['clase_id'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $docenteIds[] = $docenteId;
            $credentials[] = ['rol' => 'Docente ' . ($i + 1) . '     ', 'email' => $d['email'], 'password' => $pass];
        }

        // ─────────────────────────────────────────
        // TUTORES + ALUMNOS
        // ─────────────────────────────────────────
        $tutoresData = [
            [
                'name' => 'Rosa',   'apellidos' => 'Fernández Test', 'email' => 'tutor1@test.com', 'telefono' => '622000001',
                'alumnos' => [
                    ['nombre' => 'Mario',   'apellidos' => 'Fernández Ruiz',   'nacimiento' => '2016-04-12', 'curso' => $curso1Id, 'clase' => $clase1Id],
                    ['nombre' => 'Lucia',   'apellidos' => 'Fernández Ruiz',   'nacimiento' => '2017-09-03', 'curso' => $curso2Id, 'clase' => $clase2Id],
                ],
            ],
            [
                'name' => 'Juan',   'apellidos' => 'Sánchez Test',   'email' => 'tutor2@test.com', 'telefono' => '622000002',
                'alumnos' => [
                    ['nombre' => 'Elena',   'apellidos' => 'Sánchez Mora',     'nacimiento' => '2016-01-22', 'curso' => $curso1Id, 'clase' => $clase1Id],
                    ['nombre' => 'Pablo',   'apellidos' => 'Sánchez Mora',     'nacimiento' => '2017-06-15', 'curso' => $curso2Id, 'clase' => $clase2Id],
                ],
            ],
            [
                'name' => 'Ana',    'apellidos' => 'Romero Test',    'email' => 'tutor3@test.com', 'telefono' => '622000003',
                'alumnos' => [
                    ['nombre' => 'Carlos',  'apellidos' => 'Romero Gil',       'nacimiento' => '2016-11-08', 'curso' => $curso1Id, 'clase' => $clase1Id],
                    ['nombre' => 'Sofia',   'apellidos' => 'Romero Gil',       'nacimiento' => '2017-03-27', 'curso' => $curso2Id, 'clase' => $clase2Id],
                ],
            ],
            [
                'name' => 'Miguel', 'apellidos' => 'Torres Test',   'email' => 'tutor4@test.com', 'telefono' => '622000004',
                'alumnos' => [
                    ['nombre' => 'Nora',    'apellidos' => 'Torres Blanco',    'nacimiento' => '2016-07-19', 'curso' => $curso1Id, 'clase' => $clase1Id],
                    ['nombre' => 'Hugo',    'apellidos' => 'Torres Blanco',    'nacimiento' => '2017-12-01', 'curso' => $curso2Id, 'clase' => $clase2Id],
                ],
            ],
        ];

        foreach ($tutoresData as $i => $t) {
            $pass = 'tutor' . ($i + 1) . '1234';
            $userId = DB::table('users')->insertGetId([
                'name'              => $t['name'],
                'apellidos'         => $t['apellidos'],
                'email'             => $t['email'],
                'email_verified_at' => $now,
                'password'          => Hash::make($pass),
                'colegio_id'        => $colegioId,
                'activo'            => true,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            $tutorId = DB::table('tutores')->insertGetId([
                'telefono'   => $t['telefono'],
                'user_id'    => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            foreach ($t['alumnos'] as $a) {
                $alumnoId = DB::table('alumnos')->insertGetId([
                    'nombre'           => $a['nombre'],
                    'apellidos'        => $a['apellidos'],
                    'fecha_nacimiento' => $a['nacimiento'],
                    'colegio_id'       => $colegioId,
                    'curso_id'         => $a['curso'],
                    'clase_id'         => $a['clase'],
                    'activo'           => true,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
                DB::table('tutores_alumnos')->insert([
                    'tutor_id'   => $tutorId,
                    'alumno_id'  => $alumnoId,
                    'parentesco' => 'Padre/Madre',
                ]);
            }
            $credentials[] = ['rol' => 'Tutor ' . ($i + 1) . '       ', 'email' => $t['email'], 'password' => $pass];
        }

        // ─────────────────────────────────────────
        // IMPRIMIR CREDENCIALES EN CONSOLA
        // ─────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('╔══════════════════════════════════════════════════════╗');
        $this->command->info('║           TEST CREDENTIALS — TestSeeder              ║');
        $this->command->info('╠══════════════╦═══════════════════════╦═══════════════╣');
        $this->command->info('║ Rol          ║ Email                 ║ Password      ║');
        $this->command->info('╠══════════════╬═══════════════════════╬═══════════════╣');
        foreach ($credentials as $c) {
            $this->command->info(sprintf(
                '║ %s║ %-21s ║ %-13s ║',
                $c['rol'],
                $c['email'],
                $c['password']
            ));
        }
        $this->command->info('╚══════════════╩═══════════════════════╩═══════════════╝');
        $this->command->newLine();
    }
}