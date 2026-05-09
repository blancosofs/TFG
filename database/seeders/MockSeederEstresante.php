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
use App\Models\MaterialRepaso;
use Carbon\Carbon;

class MockSeederEstresante extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('12345678'); // Contraseña estándar para pruebas

        // ==========================================
        // 0. USUARIO ADMINISTRADOR (El que faltaba)
        // ==========================================
        User::create([
            'name' => 'Administrador TFG',
            'apellidos' => 'Atenea',
            'email' => 'admin@tfg.com',
            'password' => $password,
            'colegio_id' => null,
            'activo' => true
        ]);


        // ==========================================
        // 1. COLEGIO Y COORDINADOR
        // ==========================================
        $colegio = Colegio::create([
            'nombre'    => 'Colegio Atenea',
            'entidad'   => 'Privada Concertada',
            'direccion' => 'Calle de la Educación 45, Madrid',
            'activo'    => true
        ]);

        $userCoord = User::create([
            'name'       => 'Elena',
            'apellidos'  => 'Navarro (Coord)',
            'email'      => 'coordinacion@atenea.es',
            'password'   => $password,
            'colegio_id' => $colegio->id,
            'activo'     => true
        ]);

        $coordinador = Coordinador::create([
            'colegio_id' => $colegio->id,
            'user_id'    => $userCoord->id
        ]);

        // ==========================================
        // 2. CURSOS Y CLASES
        // ==========================================
        $curso1 = Curso::create(['nombre' => '1º ESO', 'colegio_id' => $colegio->id]);
        $curso2 = Curso::create(['nombre' => '2º ESO', 'colegio_id' => $colegio->id]);

        $clase1A = Clase::create(['nombre' => '1ºA', 'codigo_acceso' => 'ATN-1A-26', 'curso_id' => $curso1->id]);
        $clase1B = Clase::create(['nombre' => '1ºB', 'codigo_acceso' => 'ATN-1B-26', 'curso_id' => $curso1->id]);
        $clase2A = Clase::create(['nombre' => '2ºA', 'codigo_acceso' => 'ATN-2A-26', 'curso_id' => $curso2->id]);

        // ==========================================
        // 3. DOCENTES
        // ==========================================
        // Docente 1: Ciencias
        $userDocente1 = User::create([
            'name' => 'David', 'apellidos' => 'López', 
            'email' => 'dlopez@atenea.es', 'password' => $password, 'colegio_id' => $colegio->id
        ]);
        $docente1 = Docente::create([
            'telefono' => '600111222', 'colegio_id' => $colegio->id, 
            'coordinador_id' => $coordinador->id, 'user_id' => $userDocente1->id,
            'asignaturas' => 'Matemáticas, Física y Química'
        ]);

        // Docente 2: Letras
        $userDocente2 = User::create([
            'name' => 'Carmen', 'apellidos' => 'Ruiz', 
            'email' => 'cruiz@atenea.es', 'password' => $password, 'colegio_id' => $colegio->id
        ]);
        $docente2 = Docente::create([
            'telefono' => '600333444', 'colegio_id' => $colegio->id, 
            'coordinador_id' => $coordinador->id, 'user_id' => $userDocente2->id,
            'asignaturas' => 'Lengua Castellana, Historia'
        ]);

        // Asignar clases a docentes (Pivote)
        DB::table('docentes_clases')->insert([
            ['docente_id' => $docente1->id, 'clase_id' => $clase1A->id],
            ['docente_id' => $docente1->id, 'clase_id' => $clase1B->id],
            ['docente_id' => $docente2->id, 'clase_id' => $clase1A->id],
        ]);

        // ==========================================
        // 4. TUTORES (FAMILIAS)
        // ==========================================
        $userTutor = User::create([
            'name' => 'Marta', 'apellidos' => 'Sánchez', 
            'email' => 'marta.familia@gmail.com', 'password' => $password, 'colegio_id' => $colegio->id
        ]);
        $tutor1 = Tutor::create(['telefono' => '655999888', 'user_id' => $userTutor->id]);

        // ==========================================
        // 5. ALUMNOS
        // ==========================================
        $alumno1 = Alumno::create([
            'nombre' => 'Hugo', 'apellidos' => 'Martínez Sánchez', 
            'fecha_nacimiento' => '2013-05-14',
            'colegio_id' => $colegio->id, 'curso_id' => $curso1->id, 'clase_id' => $clase1A->id, 'activo' => true
        ]);

        $alumno2 = Alumno::create([
            'nombre' => 'Lucía', 'apellidos' => 'Gómez Vidal', 
            'fecha_nacimiento' => '2013-08-22',
            'colegio_id' => $colegio->id, 'curso_id' => $curso1->id, 'clase_id' => $clase1A->id, 'activo' => true
        ]);

        // Relación Tutor - Alumno (Pivote)
        DB::table('tutores_alumnos')->insert([
            ['tutor_id' => $tutor1->id, 'alumno_id' => $alumno1->id, 'parentesco' => 'Madre']
        ]);

        // ==========================================
        // 6. HORARIOS Y AUSENCIAS
        // ==========================================
        
        // Horario Base (Ligado a la ausencia posterior)
        $horarioId = DB::table('horarios')->insertGetId([
            'dia_semana' => 'lunes', 'hora_inicio' => '09:00:00', 'hora_fin' => '09:55:00',
            'docente_id' => $docente1->id, 'clase_id' => $clase1A->id,
            'created_at' => now(), 'updated_at' => now()
        ]);

        // --- NUEVOS HORARIOS AÑADIDOS PARA PRUEBAS DEL BLADE ---
        $nuevosHorarios = [
            // Continuación del Lunes para 1ºA
            ['dia_semana' => 'lunes', 'hora_inicio' => '09:55:00', 'hora_fin' => '10:50:00', 'docente_id' => $docente2->id, 'clase_id' => $clase1A->id, 'created_at' => now(), 'updated_at' => now()],
            
            // Martes
            ['dia_semana' => 'martes', 'hora_inicio' => '09:00:00', 'hora_fin' => '09:55:00', 'docente_id' => $docente2->id, 'clase_id' => $clase1A->id, 'created_at' => now(), 'updated_at' => now()],
            ['dia_semana' => 'martes', 'hora_inicio' => '10:20:00', 'hora_fin' => '11:15:00', 'docente_id' => $docente1->id, 'clase_id' => $clase1A->id, 'created_at' => now(), 'updated_at' => now()], // Después del recreo
            
            // Miércoles (Otra clase para comprobar filtros)
            ['dia_semana' => 'miercoles', 'hora_inicio' => '11:45:00', 'hora_fin' => '12:40:00', 'docente_id' => $docente1->id, 'clase_id' => $clase1B->id, 'created_at' => now(), 'updated_at' => now()],
            
            // Jueves
            ['dia_semana' => 'jueves', 'hora_inicio' => '12:40:00', 'hora_fin' => '13:35:00', 'docente_id' => $docente2->id, 'clase_id' => $clase2A->id, 'created_at' => now(), 'updated_at' => now()],
            
            // Viernes (A primera hora)
            ['dia_semana' => 'viernes', 'hora_inicio' => '08:00:00', 'hora_fin' => '08:55:00', 'docente_id' => $docente1->id, 'clase_id' => $clase1A->id, 'created_at' => now(), 'updated_at' => now()],
        ];
        
        // Insertamos todos de golpe
        DB::table('horarios')->insert($nuevosHorarios);
        // -------------------------------------------------------

        // Ausencia ligada al primer horario
        DB::table('ausencias')->insert([
            'fecha' => Carbon::yesterday(), 'tipo' => 'falta', 
            'justificada' => true, 'justificacion' => 'Cita médica (Pediatra)',
            'alumno_id' => $alumno1->id, 'docente_id' => $docente1->id, 'horario_id' => $horarioId,
            'created_at' => now(), 'updated_at' => now()
        ]);

        // ==========================================
        // 7. TABLÓN DE ANUNCIOS Y COMENTARIOS
        // ==========================================
        // Anuncio 1: Examen (De David López para 1ºA)
        $anuncioExamenId = DB::table('tablon')->insertGetId([
            'docente_id'   => $docente1->id,
            'tutor_id'     => null,
            'titulo'       => 'Examen Tema 3: Álgebra Básica',
            'categoria'    => 'Examen',
            'dirigido_a'   => 'Todos',
            'contenido'    => 'Estimados alumnos y familias, el próximo martes realizaremos la prueba escrita correspondiente al Tema 3. Repasen las ecuaciones de primer grado.',
            'clase_id'     => $clase1A->id,
            'fecha_limite' => Carbon::now()->addDays(5),
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Anuncio 2: Excursión (De Carmen Ruiz para todos)
        $anuncioEventoId = DB::table('tablon')->insertGetId([
            'docente_id'   => $docente2->id,
            'tutor_id'     => null,
            'titulo'       => 'Autorización Salida al Museo del Prado',
            'categoria'    => 'Evento',
            'dirigido_a'   => 'Solo familias',
            'contenido'    => 'Por favor, no olviden devolver firmada la autorización para la salida cultural al Museo del Prado de la próxima semana. Es imprescindible para subir al autocar.',
            'clase_id'     => null, // Nulo = Todas las clases
            'fecha_limite' => Carbon::now()->addDays(2),
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Comentario de la familia (Marta Sánchez) en el anuncio de la excursión
        DB::table('comentarios_tablon')->insert([
            'tablon_id'  => $anuncioEventoId,
            'user_id'    => $userTutor->id, // Usa el user_id de la madre
            'texto'      => 'Hugo ya la lleva firmada en la agenda, un saludo.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ==========================================
        // 8. DATOS PARA TESTEO EXTREMO DEL FRONTEND (EDGE CASES)
        // ==========================================

        // A. El Docente Fantasma (Sin clases ni asignaturas, para probar pantallas vacías)
        $userFantasma = User::create([
            'name' => 'Profesor', 'apellidos' => 'Fantasma', 
            'email' => 'vacio@atenea.es', 'password' => $password, 'colegio_id' => $colegio->id
        ]);
        Docente::create([
            'telefono' => null, 'colegio_id' => $colegio->id, 
            'coordinador_id' => $coordinador->id, 'user_id' => $userFantasma->id,
            'asignaturas' => null
        ]);

        // B. Alumno Inactivo (Dado de baja, no debería salir en las listas)
        Alumno::create([
            'nombre' => 'Alumno', 'apellidos' => 'Dado de Baja', 
            'colegio_id' => $colegio->id, 'curso_id' => $curso1->id, 'clase_id' => $clase1A->id, 
            'activo' => false // <-- ¡Clave para probar filtros!
        ]);

        // C. Anuncio Kilométrico (Para probar que el CSS de Jeremy no se rompa)
        DB::table('tablon')->insert([
            'docente_id'   => $docente1->id,
            'tutor_id'     => null,
            'titulo'       => 'ESTE ES UN TÍTULO EXTREMADAMENTE LARGO PARA COMPROBAR SI EL DISEÑO DE LAS TARJETAS DEL TABLÓN SE ROMPE O SI SE ADAPTA CORRECTAMENTE A VARIAS LÍNEAS CUANDO UN PROFESOR ESCRIBE EN MAYÚSCULAS.',
            'categoria'    => 'General',
            'dirigido_a'   => 'Todos',
            'contenido'    => str_repeat('Este es un texto muy largo para probar el scroll. ', 30),
            'clase_id'     => null,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // ==========================================
        // 9. MATERIAL DE REPASO
        // ==========================================

        // Material 1: PDF publicado, asignado a la madre de Hugo → aparece en la vista de tutor
        $mat1 = MaterialRepaso::create([
            'docente_id'              => $docente1->id,
            'colegio_id'              => $colegio->id,
            'titulo'                  => 'Ejercicios Tema 3: Ecuaciones de Primer Grado',
            'descripcion'             => 'Batería de ejercicios para repasar antes del examen del martes. Incluye soluciones al final.',
            'tipo_contenido'          => 'archivo',
            'archivo_nombre_original' => 'ejercicios_ecuaciones_t3.pdf',
            'archivo_ruta'            => 'materiales/1/mock_ejercicios_t3.pdf',
            'archivo_tamaño'          => 524288,
            'materia'                 => 'Matemáticas',
            'tema'                    => 'Ecuaciones de Primer Grado',
            'publicado'               => true,
        ]);
        DB::table('material_repaso_tutor')->insert([
            'material_repaso_id' => $mat1->id,
            'tutor_id'           => $tutor1->id,
        ]);

        // Material 2: URL externa publicada, asignada a la madre → aparece en la vista de tutor
        $mat2 = MaterialRepaso::create([
            'docente_id'     => $docente1->id,
            'colegio_id'     => $colegio->id,
            'titulo'         => 'Video Repaso: Sistemas de Ecuaciones (Khan Academy)',
            'descripcion'    => 'Video de Khan Academy sobre resolución de sistemas por sustitución. Recomendado ver antes del examen.',
            'tipo_contenido' => 'url_externa',
            'url_externa'    => 'https://es.khanacademy.org/math/algebra',
            'materia'        => 'Matemáticas',
            'tema'           => 'Sistemas de Ecuaciones',
            'publicado'      => true,
        ]);
        DB::table('material_repaso_tutor')->insert([
            'material_repaso_id' => $mat2->id,
            'tutor_id'           => $tutor1->id,
        ]);

        // Material 3: PDF en borrador (no publicado) → docente lo ve en su lista pero el tutor NO lo ve
        MaterialRepaso::create([
            'docente_id'              => $docente2->id,
            'colegio_id'              => $colegio->id,
            'titulo'                  => 'Guía de Repaso: El Romanticismo [BORRADOR]',
            'descripcion'             => 'Resumen del movimiento romántico pendiente de revisión antes de publicar.',
            'tipo_contenido'          => 'archivo',
            'archivo_nombre_original' => 'romanticismo_borrador.pdf',
            'archivo_ruta'            => 'materiales/2/mock_romanticismo.pdf',
            'archivo_tamaño'          => 204800,
            'materia'                 => 'Lengua Castellana',
            'tema'                    => 'El Romanticismo',
            'publicado'               => false,
        ]);

        // Material 4: URL publicada sin tutores asignados → docente lo ve, ningún tutor lo recibe
        MaterialRepaso::create([
            'docente_id'     => $docente2->id,
            'colegio_id'     => $colegio->id,
            'titulo'         => 'Documental: La Revolución Francesa',
            'descripcion'    => 'Documental educativo recomendado para afianzar el Tema 5. Pendiente de asignar destinatarios.',
            'tipo_contenido' => 'url_externa',
            'url_externa'    => 'https://www.youtube.com/results?search_query=revolucion+francesa+documental',
            'materia'        => 'Historia',
            'tema'           => 'La Revolución Francesa',
            'publicado'      => true,
        ]);
    }
}