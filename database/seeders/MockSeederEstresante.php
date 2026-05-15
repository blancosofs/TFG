<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MockSeederEstresante extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────
        // 1. COLEGIOS
        // ─────────────────────────────────────────
        $colegios = [
            [
                'nombre'      => 'Colegio Salesianos Domingo Savio',
                'entidad'     => 'Salesianos',
                'direccion'   => 'Calle Francos Rodríguez, 3, Madrid',
                'activo'      => true,
                'tipo'        => 'Concertado',
                'etapas'      => 'Primaria, ESO, Bachillerato, FP',
                'ciudad'      => 'Madrid',
                'comunidad'   => 'Comunidad de Madrid',
                'cp'          => '28039',
                'telefono'    => '914504550',
                'email'       => 'info@salesianos-madrid.es',
                'web'         => 'https://www.salesianos-madrid.es',
                'num_alumnos' => 1200,
                'notas'       => 'Centro de referencia en FP tecnológica.',
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'nombre'      => 'IES Ramiro de Maeztu',
                'entidad'     => 'Pública',
                'direccion'   => 'Calle Serrano Anguita, 1, Madrid',
                'activo'      => true,
                'tipo'        => 'Público',
                'etapas'      => 'ESO, Bachillerato',
                'ciudad'      => 'Madrid',
                'comunidad'   => 'Comunidad de Madrid',
                'cp'          => '28010',
                'telefono'    => '913081928',
                'email'       => 'info@iesramiro.es',
                'web'         => 'https://www.iesramiro.es',
                'num_alumnos' => 850,
                'notas'       => 'Instituto histórico del barrio de Almagro.',
                'created_at'  => now(), 'updated_at' => now(),
            ],
        ];
        DB::table('colegios')->insert($colegios);
        $colegio1 = DB::table('colegios')->where('nombre', 'Colegio Salesianos Domingo Savio')->first()->id;
        $colegio2 = DB::table('colegios')->where('nombre', 'IES Ramiro de Maeztu')->first()->id;

        // ─────────────────────────────────────────
        // 2. USERS
        // ─────────────────────────────────────────
        $users = [
            
            //Admin
            ['name' => 'Administrador',      'apellidos' => 'Pruebas Pruebas',    'email' => 'administrador@edunoly.com',   'colegio_id' => null],
            // Coordinadores
            ['name' => 'Ana',      'apellidos' => 'García López',    'email' => 'ana.coordinadora@edunoly.com',   'colegio_id' => $colegio1],
            ['name' => 'Pedro',    'apellidos' => 'Martínez Ruiz',   'email' => 'pedro.coordinador@edunoly.com',  'colegio_id' => $colegio2],
            // Docentes colegio 1
            ['name' => 'Carlos',   'apellidos' => 'Rodríguez Pérez', 'email' => 'carlos.docente@edunoly.com',     'colegio_id' => $colegio1],
            ['name' => 'María',    'apellidos' => 'Sánchez Torres',  'email' => 'maria.docente@edunoly.com',      'colegio_id' => $colegio1],
            ['name' => 'Luis',     'apellidos' => 'Fernández Gil',   'email' => 'luis.docente@edunoly.com',       'colegio_id' => $colegio1],
            ['name' => 'Elena',    'apellidos' => 'Díaz Moreno',     'email' => 'elena.docente@edunoly.com',      'colegio_id' => $colegio1],
            ['name' => 'Javier',   'apellidos' => 'López Castillo',  'email' => 'javier.docente@edunoly.com',     'colegio_id' => $colegio1],
            // Docentes colegio 2
            ['name' => 'Sofía',    'apellidos' => 'Jiménez Vega',    'email' => 'sofia.docente@edunoly.com',      'colegio_id' => $colegio2],
            ['name' => 'Roberto',  'apellidos' => 'Alonso Reyes',    'email' => 'roberto.docente@edunoly.com',    'colegio_id' => $colegio2],
            // Tutores
            ['name' => 'Carmen',   'apellidos' => 'Ruiz Blanco',     'email' => 'carmen.tutor@edunoly.com',       'colegio_id' => $colegio1],
            ['name' => 'Miguel',   'apellidos' => 'González Herrera','email' => 'miguel.tutor@edunoly.com',       'colegio_id' => $colegio1],
            ['name' => 'Patricia', 'apellidos' => 'Morales Cruz',    'email' => 'patricia.tutor@edunoly.com',     'colegio_id' => $colegio1],
            ['name' => 'Francisco','apellidos' => 'Vázquez León',    'email' => 'francisco.tutor@edunoly.com',    'colegio_id' => $colegio2],
        ];

        foreach ($users as &$u) {
            $u['password']   = Hash::make('password');
            $u['activo']     = true;
            $u['created_at'] = now();
            $u['updated_at'] = now();
        }
        DB::table('users')->insert($users);

        $getUser = fn($email) => DB::table('users')->where('email', $email)->first()->id;

        // ─────────────────────────────────────────
        // 3. COORDINADORES
        // ─────────────────────────────────────────
        DB::table('coordinadores')->insert([
            ['colegio_id' => $colegio1, 'user_id' => $getUser('ana.coordinadora@edunoly.com'),  'created_at' => now(), 'updated_at' => now()],
            ['colegio_id' => $colegio2, 'user_id' => $getUser('pedro.coordinador@edunoly.com'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        $coord1 = DB::table('coordinadores')->where('colegio_id', $colegio1)->first()->id;
        $coord2 = DB::table('coordinadores')->where('colegio_id', $colegio2)->first()->id;

        // ─────────────────────────────────────────
        // 4. DOCENTES
        // ─────────────────────────────────────────
        $docentesData = [
            ['email' => 'carlos.docente@edunoly.com',   'telefono' => '612345001', 'asignaturas' => 'Matemáticas,Física',           'colegio_id' => $colegio1, 'coordinador_id' => $coord1],
            ['email' => 'maria.docente@edunoly.com',    'telefono' => '612345002', 'asignaturas' => 'Lengua Castellana,Literatura',  'colegio_id' => $colegio1, 'coordinador_id' => $coord1],
            ['email' => 'luis.docente@edunoly.com',     'telefono' => '612345003', 'asignaturas' => 'Historia,Geografía',            'colegio_id' => $colegio1, 'coordinador_id' => $coord1],
            ['email' => 'elena.docente@edunoly.com',    'telefono' => '612345004', 'asignaturas' => 'Inglés,Francés',               'colegio_id' => $colegio1, 'coordinador_id' => $coord1],
            ['email' => 'javier.docente@edunoly.com',   'telefono' => '612345005', 'asignaturas' => 'Programación,DAM,DAW',          'colegio_id' => $colegio1, 'coordinador_id' => $coord1],
            ['email' => 'sofia.docente@edunoly.com',    'telefono' => '612345006', 'asignaturas' => 'Biología,Química',              'colegio_id' => $colegio2, 'coordinador_id' => $coord2],
            ['email' => 'roberto.docente@edunoly.com',  'telefono' => '612345007', 'asignaturas' => 'Educación Física,Salud',        'colegio_id' => $colegio2, 'coordinador_id' => $coord2],
        ];

        foreach ($docentesData as $d) {
            DB::table('docentes')->insert([
                'telefono'       => $d['telefono'],
                'asignaturas'    => $d['asignaturas'],
                'colegio_id'     => $d['colegio_id'],
                'coordinador_id' => $d['coordinador_id'],
                'user_id'        => $getUser($d['email']),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $getDocente = fn($email) => DB::table('docentes')->where('user_id', $getUser($email))->first()->id;

        // ─────────────────────────────────────────
        // 5. TUTORES
        // ─────────────────────────────────────────
        DB::table('tutores')->insert([
            ['telefono' => '622100001', 'user_id' => $getUser('carmen.tutor@edunoly.com'),    'created_at' => now(), 'updated_at' => now()],
            ['telefono' => '622100002', 'user_id' => $getUser('miguel.tutor@edunoly.com'),    'created_at' => now(), 'updated_at' => now()],
            ['telefono' => '622100003', 'user_id' => $getUser('patricia.tutor@edunoly.com'),  'created_at' => now(), 'updated_at' => now()],
            ['telefono' => '622100004', 'user_id' => $getUser('francisco.tutor@edunoly.com'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        $getTutor = fn($email) => DB::table('tutores')->where('user_id', $getUser($email))->first()->id;
        $tutor1 = $getTutor('carmen.tutor@edunoly.com');
        $tutor2 = $getTutor('miguel.tutor@edunoly.com');
        $tutor3 = $getTutor('patricia.tutor@edunoly.com');
        $tutor4 = $getTutor('francisco.tutor@edunoly.com');

        // ─────────────────────────────────────────
        // 6. CURSOS
        // ─────────────────────────────────────────
        DB::table('cursos')->insert([
            ['nombre' => '1º ESO',        'colegio_id' => $colegio1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '2º ESO',        'colegio_id' => $colegio1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '3º ESO',        'colegio_id' => $colegio1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'DAM 1º',        'colegio_id' => $colegio1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'DAM 2º',        'colegio_id' => $colegio1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '1º Bachiller',  'colegio_id' => $colegio2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '2º Bachiller',  'colegio_id' => $colegio2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $getCurso = fn($nombre, $col) => DB::table('cursos')->where('nombre', $nombre)->where('colegio_id', $col)->first()->id;
        $curso1eso  = $getCurso('1º ESO', $colegio1);
        $curso2eso  = $getCurso('2º ESO', $colegio1);
        $curso3eso  = $getCurso('3º ESO', $colegio1);
        $cursoDAM1  = $getCurso('DAM 1º', $colegio1);
        $cursoDAM2  = $getCurso('DAM 2º', $colegio1);
        $curso1bach = $getCurso('1º Bachiller', $colegio2);
        $curso2bach = $getCurso('2º Bachiller', $colegio2);

        // ─────────────────────────────────────────
        // 7. CLASES
        // ─────────────────────────────────────────
        DB::table('clases')->insert([
            ['nombre' => '1A', 'codigo_acceso' => null, 'curso_id' => $curso1eso,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '1B', 'codigo_acceso' => null, 'curso_id' => $curso1eso,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '2A', 'codigo_acceso' => null, 'curso_id' => $curso2eso,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => '3A', 'codigo_acceso' => null, 'curso_id' => $curso3eso,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'DA1','codigo_acceso' => null, 'curso_id' => $cursoDAM1,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'DA2','codigo_acceso' => null, 'curso_id' => $cursoDAM2,  'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'BA1','codigo_acceso' => null, 'curso_id' => $curso1bach, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'BA2','codigo_acceso' => null, 'curso_id' => $curso2bach, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $getClase = fn($nombre) => DB::table('clases')->where('nombre', $nombre)->first()->id;
        $clase1A = $getClase('1A');
        $clase1B = $getClase('1B');
        $clase2A = $getClase('2A');
        $clase3A = $getClase('3A');
        $claseDA1= $getClase('DA1');
        $claseDA2= $getClase('DA2');
        $claseBA1= $getClase('BA1');
        $claseBA2= $getClase('BA2');

        // ─────────────────────────────────────────
        // 8. ALUMNOS (30 alumnos repartidos)
        // ─────────────────────────────────────────
        $alumnos = [
            // 1A - 1º ESO - Colegio 1
            ['nombre' => 'Lucía',     'apellidos' => 'García Martínez',   'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-03-15'],
            ['nombre' => 'Pablo',     'apellidos' => 'Fernández Ruiz',    'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-07-22'],
            ['nombre' => 'Marta',     'apellidos' => 'López Sánchez',     'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-11-08'],
            ['nombre' => 'Diego',     'apellidos' => 'Pérez González',    'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-05-30'],
            ['nombre' => 'Valeria',   'apellidos' => 'Torres Díaz',       'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-09-14'],
            // 1B
            ['nombre' => 'Adrián',    'apellidos' => 'Moreno Castillo',   'clase_id' => $clase1B, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-01-19'],
            ['nombre' => 'Sara',      'apellidos' => 'Jiménez Vega',      'clase_id' => $clase1B, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-04-03'],
            ['nombre' => 'Hugo',      'apellidos' => 'Alonso Reyes',      'clase_id' => $clase1B, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-12-27'],
            // 2A
            ['nombre' => 'Claudia',   'apellidos' => 'Ramírez Herrera',   'clase_id' => $clase2A, 'curso_id' => $curso2eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2011-06-11'],
            ['nombre' => 'Marcos',    'apellidos' => 'Vargas León',       'clase_id' => $clase2A, 'curso_id' => $curso2eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2011-02-28'],
            ['nombre' => 'Noa',       'apellidos' => 'Cruz Blanco',       'clase_id' => $clase2A, 'curso_id' => $curso2eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2011-08-17'],
            ['nombre' => 'Álvaro',    'apellidos' => 'Serrano Muñoz',     'clase_id' => $clase2A, 'curso_id' => $curso2eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2011-10-05'],
            // 3A
            ['nombre' => 'Daniela',   'apellidos' => 'Ortega Navarro',    'clase_id' => $clase3A, 'curso_id' => $curso3eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2010-07-21'],
            ['nombre' => 'Alejandro', 'apellidos' => 'Molina Delgado',    'clase_id' => $clase3A, 'curso_id' => $curso3eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2010-03-09'],
            ['nombre' => 'Irene',     'apellidos' => 'Castro Romero',     'clase_id' => $clase3A, 'curso_id' => $curso3eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2010-11-30'],
            // DAM 1
            ['nombre' => 'Nicolás',   'apellidos' => 'Iglesias Suárez',   'clase_id' => $claseDA1,'curso_id' => $cursoDAM1,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2004-05-18'],
            ['nombre' => 'Laura',     'apellidos' => 'Rubio Flores',      'clase_id' => $claseDA1,'curso_id' => $cursoDAM1,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2004-09-07'],
            ['nombre' => 'Tomás',     'apellidos' => 'Medina Santos',     'clase_id' => $claseDA1,'curso_id' => $cursoDAM1,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2004-01-25'],
            ['nombre' => 'Beatriz',   'apellidos' => 'Guerrero Fuentes',  'clase_id' => $claseDA1,'curso_id' => $cursoDAM1,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2004-12-14'],
            // DAM 2
            ['nombre' => 'Rodrigo',   'apellidos' => 'Cano Peña',         'clase_id' => $claseDA2,'curso_id' => $cursoDAM2,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2003-04-02'],
            ['nombre' => 'Natalia',   'apellidos' => 'Vidal Lozano',      'clase_id' => $claseDA2,'curso_id' => $cursoDAM2,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2003-08-19'],
            // Bachiller colegio 2
            ['nombre' => 'Samuel',    'apellidos' => 'Gil Campos',        'clase_id' => $claseBA1,'curso_id' => $curso1bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2007-06-15'],
            ['nombre' => 'Elena',     'apellidos' => 'Prieto Ramos',      'clase_id' => $claseBA1,'curso_id' => $curso1bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2007-02-22'],
            ['nombre' => 'Andrés',    'apellidos' => 'Méndez Cabrera',    'clase_id' => $claseBA1,'curso_id' => $curso1bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2007-10-11'],
            ['nombre' => 'Carla',     'apellidos' => 'Herrero Aguilar',   'clase_id' => $claseBA2,'curso_id' => $curso2bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2006-03-30'],
            ['nombre' => 'Iván',      'apellidos' => 'Bravo Nieto',       'clase_id' => $claseBA2,'curso_id' => $curso2bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2006-07-08'],
            ['nombre' => 'Marina',    'apellidos' => 'Esteban Montoya',   'clase_id' => $claseBA2,'curso_id' => $curso2bach, 'colegio_id' => $colegio2, 'fecha_nacimiento' => '2006-01-17'],
            // Alumno inactivo para probar filtros
            ['nombre' => 'Víctor',    'apellidos' => 'Pardo Ibáñez',      'clase_id' => $clase1A, 'curso_id' => $curso1eso,  'colegio_id' => $colegio1, 'fecha_nacimiento' => '2012-06-20', 'activo' => false],
        ];

        foreach ($alumnos as $a) {
            DB::table('alumnos')->insert([
                'nombre'          => $a['nombre'],
                'apellidos'       => $a['apellidos'],
                'fecha_nacimiento'=> $a['fecha_nacimiento'],
                'colegio_id'      => $a['colegio_id'],
                'curso_id'        => $a['curso_id'],
                'clase_id'        => $a['clase_id'],
                'activo'          => $a['activo'] ?? true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // IDs de alumnos por clase
        $alumnosClase1A  = DB::table('alumnos')->where('clase_id', $clase1A)->where('activo', true)->pluck('id');
        $alumnosClase1B  = DB::table('alumnos')->where('clase_id', $clase1B)->pluck('id');
        $alumnosClase2A  = DB::table('alumnos')->where('clase_id', $clase2A)->pluck('id');
        $alumnosClaseDA1 = DB::table('alumnos')->where('clase_id', $claseDA1)->pluck('id');

        // ─────────────────────────────────────────
        // 9. DOCENTES_CLASES (qué docente imparte en qué clase)
        // ─────────────────────────────────────────
        $docCarlos  = $getDocente('carlos.docente@edunoly.com');
        $docMaria   = $getDocente('maria.docente@edunoly.com');
        $docLuis    = $getDocente('luis.docente@edunoly.com');
        $docElena   = $getDocente('elena.docente@edunoly.com');
        $docJavier  = $getDocente('javier.docente@edunoly.com');
        $docSofia   = $getDocente('sofia.docente@edunoly.com');
        $docRoberto = $getDocente('roberto.docente@edunoly.com');

        DB::table('docentes_clases')->insert([
            ['docente_id' => $docCarlos,  'clase_id' => $clase1A],
            ['docente_id' => $docCarlos,  'clase_id' => $clase1B],
            ['docente_id' => $docCarlos,  'clase_id' => $clase2A],
            ['docente_id' => $docMaria,   'clase_id' => $clase1A],
            ['docente_id' => $docMaria,   'clase_id' => $clase1B],
            ['docente_id' => $docMaria,   'clase_id' => $clase3A],
            ['docente_id' => $docLuis,    'clase_id' => $clase2A],
            ['docente_id' => $docLuis,    'clase_id' => $clase3A],
            ['docente_id' => $docElena,   'clase_id' => $clase1A],
            ['docente_id' => $docElena,   'clase_id' => $clase2A],
            ['docente_id' => $docJavier,  'clase_id' => $claseDA1],
            ['docente_id' => $docJavier,  'clase_id' => $claseDA2],
            ['docente_id' => $docSofia,   'clase_id' => $claseBA1],
            ['docente_id' => $docSofia,   'clase_id' => $claseBA2],
            ['docente_id' => $docRoberto, 'clase_id' => $claseBA1],
            ['docente_id' => $docRoberto, 'clase_id' => $claseBA2],
        ]);

        // ─────────────────────────────────────────
        // 10. HORARIOS
        // ─────────────────────────────────────────
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        $franjas = [
            ['08:00:00', '09:00:00'],
            ['09:00:00', '10:00:00'],
            ['10:30:00', '11:30:00'],
            ['11:30:00', '12:30:00'],
            ['15:00:00', '16:00:00'],
            ['16:00:00', '17:00:00'],
        ];

        $horariosCombos = [
            // Carlos - Matemáticas - 1A
            [$docCarlos, $clase1A, 'lunes',     $franjas[0], 'Matemáticas'],
            [$docCarlos, $clase1A, 'miercoles', $franjas[1], 'Matemáticas'],
            [$docCarlos, $clase1A, 'viernes',   $franjas[0], 'Física'],
            // Carlos - Matemáticas - 1B
            [$docCarlos, $clase1B, 'martes',    $franjas[0], 'Matemáticas'],
            [$docCarlos, $clase1B, 'jueves',    $franjas[1], 'Matemáticas'],
            // María - Lengua - 1A
            [$docMaria,  $clase1A, 'martes',    $franjas[2], 'Lengua Castellana'],
            [$docMaria,  $clase1A, 'jueves',    $franjas[3], 'Literatura'],
            // María - Lengua - 1B
            [$docMaria,  $clase1B, 'lunes',     $franjas[2], 'Lengua Castellana'],
            [$docMaria,  $clase1B, 'miercoles', $franjas[3], 'Literatura'],
            // Luis - Historia - 2A
            [$docLuis,   $clase2A, 'lunes',     $franjas[3], 'Historia'],
            [$docLuis,   $clase2A, 'miercoles', $franjas[4], 'Geografía'],
            // Elena - Inglés - 1A
            [$docElena,  $clase1A, 'lunes',     $franjas[4], 'Inglés'],
            [$docElena,  $clase1A, 'viernes',   $franjas[2], 'Inglés'],
            // Javier - DAM
            [$docJavier, $claseDA1,'lunes',     $franjas[0], 'Programación'],
            [$docJavier, $claseDA1,'martes',    $franjas[0], 'Bases de Datos'],
            [$docJavier, $claseDA1,'miercoles', $franjas[1], 'Entornos de Desarrollo'],
            [$docJavier, $claseDA2,'jueves',    $franjas[0], 'Acceso a Datos'],
            [$docJavier, $claseDA2,'viernes',   $franjas[1], 'Programación Multimedia'],
            // Sofía - Bachiller
            [$docSofia,  $claseBA1,'lunes',     $franjas[0], 'Biología'],
            [$docSofia,  $claseBA2,'martes',    $franjas[1], 'Química'],
            // Roberto - Educación Física
            [$docRoberto,$claseBA1,'miercoles', $franjas[4], 'Educación Física'],
            [$docRoberto,$claseBA2,'jueves',    $franjas[4], 'Educación Física'],
        ];

        $horarioIds = [];
        foreach ($horariosCombos as $h) {
            $id = DB::table('horarios')->insertGetId([
                'dia_semana'  => $h[2],
                'hora_inicio' => $h[3][0],
                'hora_fin'    => $h[3][1],
                'docente_id'  => $h[0],
                'clase_id'    => $h[1],
                'asignatura'  => $h[4],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $horarioIds[] = ['id' => $id, 'docente_id' => $h[0], 'clase_id' => $h[1]];
        }

        // ─────────────────────────────────────────
        // 11. TUTORES_ALUMNOS
        // ─────────────────────────────────────────
        DB::table('tutores_alumnos')->insert([
            ['tutor_id' => $tutor1, 'alumno_id' => $alumnosClase1A[0], 'parentesco' => 'Madre'],
            ['tutor_id' => $tutor1, 'alumno_id' => $alumnosClase1A[1], 'parentesco' => 'Madre'],
            ['tutor_id' => $tutor2, 'alumno_id' => $alumnosClase1A[2], 'parentesco' => 'Padre'],
            ['tutor_id' => $tutor2, 'alumno_id' => $alumnosClase1B[0], 'parentesco' => 'Padre'],
            ['tutor_id' => $tutor3, 'alumno_id' => $alumnosClase1A[3], 'parentesco' => 'Madre'],
            ['tutor_id' => $tutor3, 'alumno_id' => $alumnosClase2A[0], 'parentesco' => 'Madre'],
            ['tutor_id' => $tutor4, 'alumno_id' => $alumnosClase1A[4], 'parentesco' => 'Tutor legal'],
        ]);

        // ─────────────────────────────────────────
        // 12. AUSENCIAS (variadas para probar filtros)
        // ─────────────────────────────────────────
        $horario1A = collect($horarioIds)->where('clase_id', $clase1A)->first();
        $horario1B = collect($horarioIds)->where('clase_id', $clase1B)->first();
        $horarioDA = collect($horarioIds)->where('clase_id', $claseDA1)->first();

        $ausencias = [
            // Justificadas
            ['fecha' => '2026-04-07', 'tipo' => 'falta',   'justificada' => true,  'justificacion' => 'Cita médica acreditada.',           'alumno_id' => $alumnosClase1A[0], 'docente_id' => $docCarlos,  'horario_id' => $horario1A['id']],
            ['fecha' => '2026-04-08', 'tipo' => 'falta',   'justificada' => true,  'justificacion' => 'Enfermedad con justificante.',        'alumno_id' => $alumnosClase1A[1], 'docente_id' => $docMaria,   'horario_id' => $horario1A['id']],
            // No justificadas
            ['fecha' => '2026-04-14', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClase1A[2], 'docente_id' => $docCarlos,  'horario_id' => $horario1A['id']],
            ['fecha' => '2026-04-21', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClase1A[2], 'docente_id' => $docMaria,   'horario_id' => $horario1A['id']],
            ['fecha' => '2026-04-28', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClase1A[2], 'docente_id' => $docElena,   'horario_id' => $horario1A['id']],
            // Retrasos
            ['fecha' => '2026-05-05', 'tipo' => 'retraso', 'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClase1A[3], 'docente_id' => $docCarlos,  'horario_id' => $horario1A['id']],
            ['fecha' => '2026-05-06', 'tipo' => 'retraso', 'justificada' => true,  'justificacion' => 'Transporte público con incidencia.',   'alumno_id' => $alumnosClase1B[0], 'docente_id' => $docMaria,   'horario_id' => $horario1B['id']],
            ['fecha' => '2026-05-07', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClase2A[1], 'docente_id' => $docLuis,    'horario_id' => null],
            // DAM ausencias
            ['fecha' => '2026-05-08', 'tipo' => 'falta',   'justificada' => true,  'justificacion' => 'Entrevista de trabajo.',              'alumno_id' => $alumnosClaseDA1[0],'docente_id' => $docJavier,  'horario_id' => $horarioDA['id']],
            ['fecha' => '2026-05-09', 'tipo' => 'retraso', 'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClaseDA1[1],'docente_id' => $docJavier,  'horario_id' => $horarioDA['id']],
            ['fecha' => '2026-05-12', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClaseDA1[2],'docente_id' => $docJavier,  'horario_id' => $horarioDA['id']],
            ['fecha' => '2026-05-13', 'tipo' => 'falta',   'justificada' => false, 'justificacion' => null,                                  'alumno_id' => $alumnosClaseDA1[0],'docente_id' => $docJavier,  'horario_id' => $horarioDA['id']],
        ];

        foreach ($ausencias as $a) {
            DB::table('ausencias')->insert(array_merge($a, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ─────────────────────────────────────────
        // 13. TABLÓN
        // ─────────────────────────────────────────
        $tablon = [
            ['docente_id' => $docCarlos,  'tutor_id' => null,   'titulo' => 'Examen de Matemáticas semana que viene',   'categoria' => 'Examen',   'dirigido_a' => 'Todos',          'contenido' => 'El próximo lunes habrá examen de los temas 4 y 5 del libro. Estudiad con tiempo.', 'clase_id' => $clase1A, 'fecha_limite' => '2026-05-19'],
            ['docente_id' => $docMaria,   'tutor_id' => null,   'titulo' => 'Entrega trabajo de Literatura',             'categoria' => 'Tarea',    'dirigido_a' => 'Todos',          'contenido' => 'El trabajo sobre El Quijote debe entregarse antes del viernes en papel y digital.', 'clase_id' => $clase1A, 'fecha_limite' => '2026-05-16'],
            ['docente_id' => $docLuis,    'tutor_id' => null,   'titulo' => 'Visita cultural al Museo del Prado',        'categoria' => 'Evento',   'dirigido_a' => 'Solo familias',  'contenido' => 'El próximo 22 de mayo realizaremos una visita al Museo del Prado. Autorización requerida antes del 18/05.', 'clase_id' => $clase2A, 'fecha_limite' => '2026-05-18'],
            ['docente_id' => $docElena,   'tutor_id' => null,   'titulo' => 'Cambio de aula el jueves',                 'categoria' => 'General',  'dirigido_a' => 'Todos',          'contenido' => 'El jueves la clase de inglés se impartirá en el aula de idiomas (planta 2).', 'clase_id' => $clase1A, 'fecha_limite' => null],
            ['docente_id' => $docJavier,  'tutor_id' => null,   'titulo' => 'Defensa de proyectos DAM',                 'categoria' => 'Examen',   'dirigido_a' => 'Solo docentes',  'contenido' => 'Las defensas del TFG serán el 15 de junio. Preparad la documentación y el entorno de demo.', 'clase_id' => $claseDA2, 'fecha_limite' => '2026-06-15'],
            ['docente_id' => $docJavier,  'tutor_id' => null,   'titulo' => 'Material de repaso subido a la plataforma', 'categoria' => 'General',  'dirigido_a' => 'Todos',          'contenido' => 'He subido los apuntes del tema 7 en PDF y los ejercicios resueltos. Revisadlos antes del martes.', 'clase_id' => $claseDA1, 'fecha_limite' => null],
            ['docente_id' => $docCarlos,  'tutor_id' => null,   'titulo' => '¡Atención! Simulacro de evacuación',       'categoria' => 'Urgente',  'dirigido_a' => 'Todos',          'contenido' => 'Mañana a las 10:30h habrá un simulacro de evacuación. Por favor mantened la calma y seguid las instrucciones.', 'clase_id' => null, 'fecha_limite' => null],
            ['docente_id' => $docSofia,   'tutor_id' => null,   'titulo' => 'Fechas PAU 2026',                          'categoria' => 'Examen',   'dirigido_a' => 'Solo familias',  'contenido' => 'Las pruebas de acceso a la universidad (EBAU) serán del 9 al 11 de junio. Os adjunto el calendario oficial.', 'clase_id' => $claseBA2, 'fecha_limite' => '2026-06-09'],
        ];

        $tablonIds = [];
        foreach ($tablon as $t) {
            $tablonIds[] = DB::table('tablon')->insertGetId(array_merge($t, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ─────────────────────────────────────────
        // 14. COMENTARIOS TABLÓN
        // ─────────────────────────────────────────
        $userCarlos  = $getUser('carlos.docente@edunoly.com');
        $userMaria   = $getUser('maria.docente@edunoly.com');
        $userCarmen  = $getUser('carmen.tutor@edunoly.com');
        $userMiguel  = $getUser('miguel.tutor@edunoly.com');
        $userAna     = $getUser('ana.coordinadora@edunoly.com');

        DB::table('comentarios_tablon')->insert([
            ['tablon_id' => $tablonIds[0], 'user_id' => $userCarmen,  'texto' => '¿El examen incluye los ejercicios del libro también?',     'created_at' => now(), 'updated_at' => now()],
            ['tablon_id' => $tablonIds[0], 'user_id' => $userCarlos,  'texto' => 'Sí, especialmente los de las páginas 87 a 95.',            'created_at' => now(), 'updated_at' => now()],
            ['tablon_id' => $tablonIds[0], 'user_id' => $userMiguel,  'texto' => 'Gracias por el aviso, mi hijo ya está estudiando.',        'created_at' => now(), 'updated_at' => now()],
            ['tablon_id' => $tablonIds[2], 'user_id' => $userCarmen,  'texto' => '¿Hay coste adicional para la visita?',                    'created_at' => now(), 'updated_at' => now()],
            ['tablon_id' => $tablonIds[2], 'user_id' => $userLuis = $getUser('luis.docente@edunoly.com'),  'texto' => 'La entrada es gratuita para estudiantes. Solo necesitáis la autorización.', 'created_at' => now(), 'updated_at' => now()],
            ['tablon_id' => $tablonIds[6], 'user_id' => $userAna,     'texto' => 'Recordad que el punto de encuentro es el patio principal.','created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────
        // 15. MATERIALES DE REPASO
        // ─────────────────────────────────────────
        $materiales = [
            ['docente_id' => $docCarlos,  'colegio_id' => $colegio1, 'titulo' => 'Apuntes Tema 4 - Ecuaciones',         'descripcion' => 'Resumen completo del tema 4 con ejemplos resueltos.',    'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/ecuaciones',    'materia' => 'Matemáticas',       'tema' => 'Ecuaciones de segundo grado', 'publicado' => true],
            ['docente_id' => $docCarlos,  'colegio_id' => $colegio1, 'titulo' => 'Ejercicios Física - Cinemática',       'descripcion' => 'Colección de 30 ejercicios con soluciones.',              'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/cinematica',    'materia' => 'Física',             'tema' => 'Cinemática',           'publicado' => true],
            ['docente_id' => $docMaria,   'colegio_id' => $colegio1, 'titulo' => 'Guía de análisis literario',           'descripcion' => 'Cómo analizar un texto literario paso a paso.',          'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/literario',     'materia' => 'Literatura',         'tema' => 'Análisis de textos',   'publicado' => true],
            ['docente_id' => $docElena,   'colegio_id' => $colegio1, 'titulo' => 'Phrasal Verbs más comunes',            'descripcion' => 'Lista de los 100 phrasal verbs más usados en examen.',   'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/phrasalverbs',  'materia' => 'Inglés',             'tema' => 'Phrasal Verbs',        'publicado' => true],
            ['docente_id' => $docJavier,  'colegio_id' => $colegio1, 'titulo' => 'Apuntes Laravel - MVC y Rutas',        'descripcion' => 'Todo lo que necesitas saber sobre el patrón MVC.',       'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/laravel',       'materia' => 'Programación',       'tema' => 'Laravel Framework',    'publicado' => true],
            ['docente_id' => $docJavier,  'colegio_id' => $colegio1, 'titulo' => 'Cheatsheet Git y GitHub',              'descripcion' => 'Comandos esenciales de Git para el día a día.',          'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/git',           'materia' => 'Entornos de Desarrollo', 'tema' => 'Control de versiones','publicado' => true],
            ['docente_id' => $docJavier,  'colegio_id' => $colegio1, 'titulo' => 'Borrador proyecto - NO PUBLICADO',     'descripcion' => 'Material en revisión, no mostrar aún.',                  'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/borrador',      'materia' => 'DAM',                'tema' => 'Proyecto final',       'publicado' => false],
            ['docente_id' => $docSofia,   'colegio_id' => $colegio2, 'titulo' => 'Resumen Biología - Genética',          'descripcion' => 'Mendel, ADN y herencia.',                                'tipo_contenido' => 'url_externa',  'url_externa' => 'https://drive.google.com/fake/genetica',      'materia' => 'Biología',           'tema' => 'Genética',             'publicado' => true],
        ];

        $materialIds = [];
        foreach ($materiales as $m) {
            $materialIds[] = DB::table('materiales_repaso')->insertGetId(array_merge($m, [
                'archivo_nombre_original' => null,
                'archivo_ruta'            => null,
                'archivo_tamaño'          => null,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]));
        }

        // ─────────────────────────────────────────
        // 16. MATERIAL_REPASO_TUTOR
        // ─────────────────────────────────────────
        DB::table('material_repaso_tutor')->insert([
            ['material_repaso_id' => $materialIds[0], 'tutor_id' => $tutor1],
            ['material_repaso_id' => $materialIds[0], 'tutor_id' => $tutor2],
            ['material_repaso_id' => $materialIds[2], 'tutor_id' => $tutor1],
            ['material_repaso_id' => $materialIds[3], 'tutor_id' => $tutor3],
            ['material_repaso_id' => $materialIds[7], 'tutor_id' => $tutor4],
        ]);

        $this->command->info('✅ MockDataSeeder ejecutado correctamente.');
        $this->command->info('');
        $this->command->info('👤 USUARIOS DE PRUEBA (password: password)');
        $this->command->info('   Coordinador C1 → ana.coordinadora@edunoly.com');
        $this->command->info('   Coordinador C2 → pedro.coordinador@edunoly.com');
        $this->command->info('   Docente       → carlos.docente@edunoly.com');
        $this->command->info('   Docente DAM   → javier.docente@edunoly.com');
        $this->command->info('   Tutor         → carmen.tutor@edunoly.com');
        $this->command->info('');
        $this->command->info('📊 DATOS GENERADOS:');
        $this->command->info('   2 colegios, 13 usuarios, 7 docentes, 4 tutores');
        $this->command->info('   7 cursos, 8 clases, 28 alumnos');
        $this->command->info('   22 horarios, 12 ausencias');
        $this->command->info('   8 entradas de tablón, 6 comentarios');
        $this->command->info('   8 materiales de repaso (1 no publicado)');
    }
}
