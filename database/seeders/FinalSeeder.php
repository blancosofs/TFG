<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FinalSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = [];
        $now = now();
        $defaultPassword = Hash::make('password');

        // ─────────────────────────────────────────
        // 0. SUPER ADMINISTRADOR GLOBAL
        // ─────────────────────────────────────────
        DB::table('users')->insert([
            'name'              => 'SuperAdmin',
            'apellidos'         => 'Edunoly Global',
            'email'             => 'admin@edunoly.com',
            'email_verified_at' => $now,
            'password'          => Hash::make('edunoly'),
            'colegio_id'        => null,
            'activo'            => true,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        $credentials[] = ['colegio' => 'GLOBAL', 'rol' => 'Super Admin', 'email' => 'admin@edunoly.com', 'pass' => 'edunoly'];

        // ─────────────────────────────────────────
        // COLEGIO 1: SALESIANOS DOMINGO SAVIO
        // ─────────────────────────────────────────
        $col1Id = DB::table('colegios')->insertGetId([
            'nombre'      => 'Colegio Salesianos Domingo Savio',
            'entidad'     => 'Salesianos',
            'direccion'   => 'C/ Domingo Savio, 2',
            'ciudad'      => 'Madrid',
            'comunidad'   => 'Comunidad de Madrid',
            'cp'          => '28032',
            'telefono'    => '913683100',
            'email'       => 'colegio@domingosavio.com',
            'tipo'        => 'Concertado',
            'etapas'      => 'Todas hasta FP',
            'web'         => 'https://salesianosdosa.com',
            'num_alumnos' => 5000,
            'activo'      => true,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // Coordinador Colegio 1
        $coord1UserId = DB::table('users')->insertGetId([
            'name'              => 'Coordinador',
            'apellidos'         => 'Domingo Savio',
            'email'             => 'coordinador.salesianosdosa@edunoly.com',
            'email_verified_at' => $now,
            'password'          => $defaultPassword,
            'colegio_id'        => $col1Id,
            'activo'            => true,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        $coord1Id = DB::table('coordinadores')->insertGetId([
            'colegio_id' => $col1Id, 'user_id' => $coord1UserId, 'created_at' => $now, 'updated_at' => $now
        ]);
        $credentials[] = ['colegio' => 'Salesianos', 'rol' => 'Coordinador', 'email' => 'coordinador.salesianosdosa@edunoly.com', 'pass' => 'password'];

        // Cursos y Clases Colegio 1
        $curso1f = DB::table('cursos')->insertGetId(['nombre' => '1º CFGS', 'colegio_id' => $col1Id, 'created_at' => $now, 'updated_at' => $now]);
        $curso2f = DB::table('cursos')->insertGetId(['nombre' => '2º CFGS', 'colegio_id' => $col1Id, 'created_at' => $now, 'updated_at' => $now]);

        $clase1DAM = DB::table('clases')->insertGetId(['nombre' => '1DAM', 'curso_id' => $curso1f, 'created_at' => $now, 'updated_at' => $now]);
        $clase1TES = DB::table('clases')->insertGetId(['nombre' => '1TESEAS', 'curso_id' => $curso1f, 'created_at' => $now, 'updated_at' => $now]);
        $clase1SST = DB::table('clases')->insertGetId(['nombre' => '1TSSTI', 'curso_id' => $curso1f, 'created_at' => $now, 'updated_at' => $now]);

        $clase2DAM = DB::table('clases')->insertGetId(['nombre' => '2DAM', 'curso_id' => $curso2f, 'created_at' => $now, 'updated_at' => $now]);
        $clase2TEA = DB::table('clases')->insertGetId(['nombre' => '2TEAS', 'curso_id' => $curso2f, 'created_at' => $now, 'updated_at' => $now]);
        $clase2SST = DB::table('clases')->insertGetId(['nombre' => '2TSSTI', 'curso_id' => $curso2f, 'created_at' => $now, 'updated_at' => $now]);

        // Docentes Colegio 1
        $docentesC1 = [
            ['name' => 'Samuel',  'email' => 'samuel.docente@edunoly.com',  'asignaturas' => 'Programación', 'clases' => [$clase1DAM, $clase2DAM]],
            ['name' => 'Carlos',  'email' => 'carlos.docente@edunoly.com',  'asignaturas' => 'Valoración Condición Física', 'clases' => [$clase1TES, $clase2TEA]],
            ['name' => 'María',   'email' => 'maria.docente@edunoly.com',   'asignaturas' => 'Sistemas Informáticos', 'clases' => [$clase1SST, $clase2SST]],
            ['name' => 'Alberto', 'email' => 'alberto.docente@edunoly.com', 'asignaturas' => 'Inglés Técnico', 'clases' => [$clase1DAM, $clase2DAM, $clase1TES, $clase2TEA, $clase1SST, $clase2SST]]
        ];

        $docenteC1Ids = [];
        foreach ($docentesC1 as $d) {
            $uId = DB::table('users')->insertGetId([
                'name' => $d['name'], 'apellidos' => 'Docente C1', 'email' => $d['email'], 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col1Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now
            ]);
            $docId = DB::table('docentes')->insertGetId([
                'telefono' => '600111222', 'asignaturas' => $d['asignaturas'], 'colegio_id' => $col1Id, 'coordinador_id' => $coord1Id, 'user_id' => $uId, 'created_at' => $now, 'updated_at' => $now
            ]);
            $docenteC1Ids[$d['email']] = $docId;

            foreach ($d['clases'] as $claseId) {
                DB::table('docentes_clases')->insert(['docente_id' => $docId, 'clase_id' => $claseId]);
            }
            $credentials[] = ['colegio' => 'Salesianos', 'rol' => 'Docente (' . $d['name'] . ')', 'email' => $d['email'], 'pass' => 'password'];
        }

        // Horarios Mañana Colegio 1 (Ejemplo Franjas)
        $franjasManana = [['08:30:00', '09:30:00'], ['09:30:00', '10:30:00']];
        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        
        foreach ($docentesC1 as $d) {
            $docId = $docenteC1Ids[$d['email']];
            foreach ($d['clases'] as $claseId) {
                DB::table('horarios')->insert([
                    'dia_semana' => $diasSemana[array_rand($diasSemana)], 'hora_inicio' => $franjasManana[0][0], 'hora_fin' => $franjasManana[0][1], 'asignatura' => $d['asignaturas'], 'docente_id' => $docId, 'clase_id' => $claseId, 'created_at' => $now, 'updated_at' => $now
                ]);
            }
        }

        // Casos Especiales de Alumnos y Familias (2DAM / 1DAM)
        // Caso A: Sofia Blanco Calsina (2 Tutores)
        $uRuth = DB::table('users')->insertGetId(['name' => 'Ruth', 'apellidos' => 'Tutor A', 'email' => 'ruth.tutor@edunoly.com', 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col1Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        $tRuth = DB::table('tutores')->insertGetId(['telefono' => '655000001', 'user_id' => $uRuth, 'created_at' => $now, 'updated_at' => $now]);
        $uRafael = DB::table('users')->insertGetId(['name' => 'Rafael', 'apellidos' => 'Tutor A2', 'email' => 'rafael.tutor@edunoly.com', 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col1Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        $tRafael = DB::table('tutores')->insertGetId(['telefono' => '655000002', 'user_id' => $uRafael, 'created_at' => $now, 'updated_at' => $now]);

        $alSofia = DB::table('alumnos')->insertGetId(['nombre' => 'Sofia', 'apellidos' => 'Blanco Calsina', 'fecha_nacimiento' => '2004-05-13', 'colegio_id' => $col1Id, 'curso_id' => $curso2f, 'clase_id' => $clase2DAM, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('tutores_alumnos')->insert([['tutor_id' => $tRuth, 'alumno_id' => $alSofia, 'parentesco' => 'Madre'], ['tutor_id' => $tRafael, 'alumno_id' => $alSofia, 'parentesco' => 'Padre']]);
        $credentials[] = ['colegio' => 'Salesianos', 'rol' => 'Tutor (Madre)', 'email' => 'ruth.tutor@edunoly.com', 'pass' => 'password'];
        $credentials[] = ['colegio' => 'Salesianos', 'rol' => 'Tutor (Padre)', 'email' => 'rafael.tutor@edunoly.com', 'pass' => 'password'];

        // Caso B1 y B2: Comparten tutora Patricia
        $uPatricia = DB::table('users')->insertGetId(['name' => 'Patricia', 'apellidos' => 'Tutor B', 'email' => 'patricia.tutor@edunoly.com', 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col1Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        $tPatricia = DB::table('tutores')->insertGetId(['telefono' => '655000003', 'user_id' => $uPatricia, 'created_at' => $now, 'updated_at' => $now]);

        $alAshley = DB::table('alumnos')->insertGetId(['nombre' => 'Ashley Laura', 'apellidos' => 'Leon Espinoza', 'fecha_nacimiento' => '2005-01-20', 'colegio_id' => $col1Id, 'curso_id' => $curso2f, 'clase_id' => $clase2DAM, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        $alJeremy = DB::table('alumnos')->insertGetId(['nombre' => 'Jeremy', 'apellidos' => 'Narvaez Lobato', 'fecha_nacimiento' => '2004-11-02', 'colegio_id' => $col1Id, 'curso_id' => $curso2f, 'clase_id' => $clase2DAM, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('tutores_alumnos')->insert([['tutor_id' => $tPatricia, 'alumno_id' => $alAshley, 'parentesco' => 'Madre'], ['tutor_id' => $tPatricia, 'alumno_id' => $alJeremy, 'parentesco' => 'Madre']]);
        $credentials[] = ['colegio' => 'Salesianos', 'rol' => 'Tutor Compartido', 'email' => 'patricia.tutor@edunoly.com', 'pass' => 'password'];

        // Rellenar resto de aulas (3 alumnos por docente en otras clases de ejemplo)
        $clasesRestantes = [$clase1DAM, $clase1TES, $clase1SST, $clase2TEA, $clase2SST];
        foreach ($clasesRestantes as $idx => $cId) {
            $alId = DB::table('alumnos')->insertGetId(['nombre' => 'AlumnoGeneric', 'apellidos' => 'C1-' . $idx, 'fecha_nacimiento' => '2005-06-15', 'colegio_id' => $col1Id, 'curso_id' => ($idx > 2 ? $curso2f : $curso1f), 'clase_id' => $cId, 'activo' => true, 'created_at' => $now, 'updated_at' => $now]);
            DB::table('tutores_alumnos')->insert(['tutor_id' => $tPatricia, 'alumno_id' => $alId, 'parentesco' => 'Tutor legal']);
        }

        // Anuncios Tablón Colegio 1 (Adaptados a las columnas reales: docente_id)
        $anunciosC1 = [
            [
                'docente_id' => $docenteC1Ids['samuel.docente@edunoly.com'], 
                'tutor_id'   => null,
                'clase_id'   => null, 
                'titulo'     => 'Mensaje de bienvenida general', 
                'categoria'  => 'General', 
                'dirigido_a' => 'Todos', 
                'contenido'  => '¡Bienvenidos al nuevo portal Edunoly! Desde hoy coordinaremos las asistencias y comunicaciones a través de esta plataforma.'
            ],
            [
                'docente_id' => $docenteC1Ids['samuel.docente@edunoly.com'], 
                'tutor_id'   => null,
                'clase_id'   => $clase2DAM, 
                'titulo'     => 'Urgente: Entrega final TFG', 
                'categoria'  => 'Urgente', 
                'dirigido_a' => 'Todos', 
                'contenido'  => 'Recordatorio importante: La entrega final y defensa de los proyectos de TFG de 2DAM se realizará el próximo 18 de mayo. Revisad que los entornos de despliegue estén operativos.'
            ]
        ];
        foreach ($anunciosC1 as $an) {
            DB::table('tablon')->insert(array_merge($an, ['created_at' => $now, 'updated_at' => $now]));
        }

        // Ausencias Colegio 1 (2DAM)
        $horarioActivo = DB::table('horarios')->where('clase_id', $clase2DAM)->first()->id;
        $docSamuelId = $docenteC1Ids['samuel.docente@edunoly.com'];

        DB::table('ausencias')->insert([
            ['fecha' => '2026-05-14', 'tipo' => 'falta', 'justificada' => true, 'justificacion' => 'Cita médica justificable', 'alumno_id' => $alSofia, 'docente_id' => $docSamuelId, 'horario_id' => $horarioActivo, 'created_at' => $now, 'updated_at' => $now],
            ['fecha' => '2026-05-14', 'tipo' => 'falta', 'justificada' => false, 'justificacion' => null, 'alumno_id' => $alAshley, 'docente_id' => $docSamuelId, 'horario_id' => $horarioActivo, 'created_at' => $now, 'updated_at' => $now],
            ['fecha' => '2026-05-15', 'tipo' => 'falta', 'justificada' => false, 'justificacion' => null, 'alumno_id' => $alJeremy, 'docente_id' => $docSamuelId, 'horario_id' => $horarioActivo, 'created_at' => $now, 'updated_at' => $now],
            ['fecha' => '2026-05-15', 'tipo' => 'retraso', 'justificada' => true, 'justificacion' => 'Cita médica justificable', 'alumno_id' => $alSofia, 'docente_id' => $docSamuelId, 'horario_id' => $horarioActivo, 'created_at' => $now, 'updated_at' => $now],
            ['fecha' => '2026-05-15', 'tipo' => 'retraso', 'justificada' => false, 'justificacion' => null, 'alumno_id' => $alAshley, 'docente_id' => $docSamuelId, 'horario_id' => $horarioActivo, 'created_at' => $now, 'updated_at' => $now],
        ]);


        // ─────────────────────────────────────────
        // COLEGIO 2: APOYO CRISTO DE LA GUÍA
        // ─────────────────────────────────────────
        $col2Id = DB::table('colegios')->insertGetId([
            'nombre'      => 'Apoyo Cristo de la Guía',
            'entidad'     => 'Público',
            'direccion'   => 'C/ de Casalarreina',
            'ciudad'      => 'Madrid',
            'comunidad'   => 'Comunidad de Madrid',
            'cp'          => '28032',
            'telefono'    => '913081928',
            'email'       => 'apoyo.cristoguia@edunoly.com',
            'tipo'        => 'Público',
            'etapas'      => 'Infantil, Primaria',
            'web'         => 'https://cristoguia.es',
            'num_alumnos' => 20,
            'activo'      => true,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // Coordinador Colegio 2
        $coord2UserId = DB::table('users')->insertGetId([
            'name'              => 'Coordinador',
            'apellidos'         => 'Cristo de la Guía',
            'email'             => 'coordinador.cristoguia@edunoly.com',
            'email_verified_at' => $now,
            'password'          => $defaultPassword,
            'colegio_id'        => $col2Id,
            'activo'            => true,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        $coord2Id = DB::table('coordinadores')->insertGetId([
            'colegio_id' => $col2Id, 'user_id' => $coord2UserId, 'created_at' => $now, 'updated_at' => $now
        ]);
        $credentials[] = ['colegio' => 'Cristo Guía', 'rol' => 'Coordinador', 'email' => 'coordinador.cristoguia@edunoly.com', 'pass' => 'password'];

        // Curso y Clases Colegio 2
        $cursoPrimaria = DB::table('cursos')->insertGetId(['nombre' => 'Educación Primaria', 'colegio_id' => $col2Id, 'created_at' => $now, 'updated_at' => $now]);
        
        $clasePekes = DB::table('clases')->insertGetId(['nombre' => 'Pequenos', 'curso_id' => $cursoPrimaria, 'created_at' => $now, 'updated_at' => $now]);
        $claseMedis = DB::table('clases')->insertGetId(['nombre' => 'Medianos', 'curso_id' => $cursoPrimaria, 'created_at' => $now, 'updated_at' => $now]);
        $claseGrand = DB::table('clases')->insertGetId(['nombre' => 'Grandes', 'curso_id' => $cursoPrimaria, 'created_at' => $now, 'updated_at' => $now]);
        $clasesC2 = [$clasePekes, $claseMedis, $claseGrand];

        // Docentes Cooperativos Colegio 2 (Comparten las 3 clases)
        $docentesC2 = [
            ['name' => 'Sofía', 'email' => 'sofia.docente@edunoly.com', 'asignaturas' => 'Ingles'],
            ['name' => 'Mapi',  'email' => 'mapi.docente@edunoly.com',  'asignaturas' => 'Mates, Lengua'],
            ['name' => 'Puri',  'email' => 'puri.docente@edunoly.com',  'asignaturas' => 'Ciencias']
        ];

        $docenteC2Ids = [];
        foreach ($docentesC2 as $d) {
            $uId = DB::table('users')->insertGetId([
                'name' => $d['name'], 'apellidos' => 'Docente C2', 'email' => $d['email'], 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col2Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now
            ]);
            $docId = DB::table('docentes')->insertGetId([
                'telefono' => '600222333', 'asignaturas' => $d['asignaturas'], 'colegio_id' => $col2Id, 'coordinador_id' => $coord2Id, 'user_id' => $uId, 'created_at' => $now, 'updated_at' => $now
            ]);
            $docenteC2Ids[] = $docId;

            foreach ($clasesC2 as $cId) {
                DB::table('docentes_clases')->insert(['docente_id' => $docId, 'clase_id' => $cId]);
            }
            $credentials[] = ['colegio' => 'Cristo Guía', 'rol' => 'Docente (' . $d['name'] . ')', 'email' => $d['email'], 'pass' => 'password'];
        }

        // Horarios de Tarde Colegio 2 (16:00 a 18:00)
        foreach ($docenteC2Ids as $idx => $docId) {
            foreach ($clasesC2 as $cId) {
                DB::table('horarios')->insert([
                    'dia_semana' => 'lunes', 'hora_inicio' => '16:00:00', 'hora_fin' => '18:00:00', 'asignatura' => 'Apoyo Educativo', 'docente_id' => $docId, 'clase_id' => $cId, 'created_at' => $now, 'updated_at' => $now
                ]);
            }
        }

        // Volumen de Carga: 25 Tutores Independientes
        $tutorC2Ids = [];
        for ($i = 1; $i <= 25; $i++) {
            $emailTutor = sprintf('cg_tutor_%02d@edunoly.com', $i);
            $uTId = DB::table('users')->insertGetId([
                'name' => 'TutorCG_' . $i, 'apellidos' => 'Familiar', 'email' => $emailTutor, 'email_verified_at' => $now, 'password' => $defaultPassword, 'colegio_id' => $col2Id, 'activo' => true, 'created_at' => $now, 'updated_at' => $now
            ]);
            $tutorC2Ids[] = DB::table('tutores')->insertGetId([
                'telefono' => '6112233' . sprintf('%02d', $i), 'user_id' => $uTId, 'created_at' => $now, 'updated_at' => $now
            ]);
            if ($i <= 3) { // Añadir un par al log de consola de muestra
                $credentials[] = ['colegio' => 'Cristo Guía', 'rol' => 'Tutor Relacional', 'email' => $emailTutor, 'pass' => 'password'];
            }
        }

        // Volumen de Carga: 20 Alumnos distribuidos entre las 3 clases
        for ($i = 1; $i <= 20; $i++) {
            $claseAsignada = $clasesC2[($i % 3)];
            $alId = DB::table('alumnos')->insertGetId([
                'nombre' => 'CG_Alumno_' . sprintf('%02d', $i), 'apellidos' => 'De Prueba', 'fecha_nacimiento' => '2018-04-10', 'colegio_id' => $col2Id, 'curso_id' => $cursoPrimaria, 'clase_id' => $claseAsignada, 'activo' => true, 'created_at' => $now, 'updated_at' => $now
            ]);

            // Mapeo dinámico: Garantiza que se usen los 25 familiares (algunos alumnos tendrán más de un tutor)
            DB::table('tutores_alumnos')->insert(['tutor_id' => $tutorC2Ids[($i - 1)], 'alumno_id' => $alId, 'parentesco' => 'Madre/Padre']);
            if ($i <= 5) {
                DB::table('tutores_alumnos')->insert(['tutor_id' => $tutorC2Ids[($i + 19)], 'alumno_id' => $alId, 'parentesco' => 'Abuelo/a']);
            }

            // Inyectar algunas ausencias aleatorias a este grupo de apoyo
            if ($i <= 3) {
                DB::table('ausencias')->insert([
                    ['fecha' => '2026-05-14', 'tipo' => 'falta', 'justificada' => true, 'justificacion' => 'Cita médica justificable', 'alumno_id' => $alId, 'docente_id' => $docenteC2Ids[0], 'horario_id' => null, 'created_at' => $now, 'updated_at' => $now],
                    ['fecha' => '2026-05-15', 'tipo' => 'retraso', 'justificada' => false, 'justificacion' => null, 'alumno_id' => $alId, 'docente_id' => $docenteC2Ids[0], 'horario_id' => null, 'created_at' => $now, 'updated_at' => $now]
                ]);
            }
        }

        // ─────────────────────────────────────────
        // IMPRESIÓN ELEGANTE EN CONSOLA AL TERMINAR
        // ─────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('╔════════════════════════════════════════════════════════════════════════════════════╗');
        $this->command->info('║                   🎯 EDUNOLY PLATFORM - SEED COMPLETO EXITOSO                      ║');
        $this->command->info('╠═════════════════╦══════════════════════════╦══════════════════════════════╦════════════╣');
        $this->command->info('║ Colegio         ║ Rol / Tipo               ║ Email                        ║ Password   ║');
        $this->command->info('╠═════════════════╬══════════════════════════╬══════════════════════════════╬════════════╣');
        foreach ($credentials as $c) {
            $this->command->info(sprintf(
                '║ %-15s ║ %-24s ║ %-28s ║ %-10s ║',
                $c['colegio'],
                $c['rol'],
                $c['email'],
                $c['pass']
            ));
        }
        $this->command->info('╚═════════════════╩══════════════════════════╩══════════════════════════════╩════════════╝');
        $this->command->newLine();
    }
}