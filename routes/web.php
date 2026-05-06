<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Formularios\ContactoController; //pones aqui la ruta completa porque esta en carpeta
use App\Http\Controllers\toDatabase\AdminController; //pones aqui la ruta completa porque esta en carpeta
//use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TutorController; 

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS Y VISTAS ESTÁTICAS 
|--------------------------------------------------------------------------
*/
Route::view('/', 'PaginaInicio')->name('index');
Route::view('/contacto', 'PaginaContacto')->name('contacto');
Route::view('/unete', 'PaginaUnete')->name('unete');
Route::view('/configuracion', 'configuracion')->name('config');

// Formulario de Contacto (Público)
Route::post('/contacto-enviar', [ContactoController::class, 'enviarConsulta'])->name('contacto.enviar');

/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Solo usuarios logueados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // ZONA DOCENTES
    Route::middleware(['role:docente'])->group(function () {
        // Vistas
        Route::view('/calendario', 'calendario')->name('calendario');
        Route::view('/perfilDocente', 'perfilProfesor')->name('perfil');
        Route::view('/pasarLista', 'pasarLista')->name('pasarLista');
        
        // CRUD de Docentes
        Route::prefix('profesor')->as('profesor.')->group(function () {
            Route::resource('mis-clases', ClaseController::class)->only(['index', 'show']);
            Route::resource('alumnos', AlumnoController::class)->only(['show']);
            Route::resource('tutores', TutorController::class)->only(['show']);
            Route::resource('horarios', HorarioController::class)->only(['index']);
            Route::resource('ausencias', AusenciaController::class)->only(['create', 'store', 'index']);
            
        });
    });

    // ZONA COORDINADORES
    Route::middleware(['role:coordinador'])->group(function () {
        // Vistas
        Route::view('/dashboard', 'coordinador')->name('coordinador');
        Route::view('/perfilCoordinador', 'perfilCoordinador')->name('perfilCoordinador');

        // CRUD de Coordinadores
        Route::prefix('coordinacion')->as('coordinacion.')->group(function () {
            Route::resource('cursos', CursoController::class);
            Route::resource('clases', ClaseController::class);
            Route::resource('horarios', HorarioController::class);
            Route::resource('alumnos', AlumnoController::class);
            Route::resource('docentes', DocenteController::class);
            Route::resource('tutores', TutorController::class);
            Route::resource('ausencias', AusenciaController::class);
            
        });

    });

    // ZONA FAMILIAS (TUTORES)
    Route::middleware(['role:tutor'])->group(function () {
        // Vistas
        Route::view('/familiar', 'perfilFamilia');
        Route::view('/perfilFamilia', 'perfilFamilia')->name('perfilFamilia');

        // CRUD de Familias
        Route::prefix('familia')->group(function () {
            Route::resource('mis-hijos', AlumnoController::class)->only(['index', 'show']);
            Route::resource('profesores', DocenteController::class)->only(['index', 'show']);
            Route::resource('ausencias', AusenciaController::class)->only(['index', 'create', 'store', 'update', 'edit']);
        });
    });

    // ZONA ADMIN GLOBAL
    Route::middleware(['role:admin'])->group(function () {
        //Formularios
        Route::post('/registro', [AdminController::class, 'registro'])->name('solicitud.enviar'); //poner la clase y funcion que usas

        // Vistas
        Route::view('/admin', 'admin')->name('admin');
        Route::view('/perfilAdmin', 'perfilAdmin')->name('perfilAdmin');
        Route::view('/registro', 'PaginaUnete')->name('solicitud.enviar');//ver registro

        // CRUD de Admin
        Route::prefix('admin')->group(function () {
        Route::resource('colegios', ColegioController::class);
        Route::resource('coordinadores', CoordinadorController::class);

        });
    });

});

// =========================================================
// RUTAS API PARA EL FRONTEND (Manejadas por sesión Web)
// =========================================================
Route::prefix('api')->middleware(['auth'])->group(function () {

    // Cerrar sesión desde JS
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['ok' => true]);
    });

    // 1. Ruta para comprobar quién está logueado
    Route::get('/me', function (Request $request) {
        
    //$user = $request->user(); //Esto es lo que hace realmente pero se puede poner como en la sig. linea!
        $user = Auth::user();
        
        // Calculamos el rol exacto
        $rol = 'sin_rol';
        if (is_null($user->colegio_id)) $rol = 'admin';
        elseif ($user->coordinador) $rol = 'coordinador';
        elseif ($user->docente) $rol = 'docente';
        elseif ($user->tutor) $rol = 'tutor';

        return response()->json([
            'id'         => $user->id,
            'nombre'     => $user->name,
            'apellidos'  => $user->apellidos,
            'email'      => $user->email,
            'colegio_id' => $user->colegio_id,
            'rol'        => $rol
        ]);
    });

    // 2. Rutas para el Frontend del Coordinador
    Route::get('/alumnos', [AlumnoController::class, 'index']);
    Route::post('/alumnos', [AlumnoController::class, 'store']);
    Route::put('/alumnos/{id}', [AlumnoController::class, 'update']);
    Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy']);

    Route::get('/docentes', [DocenteController::class, 'index']);
    Route::post('/docentes', [DocenteController::class, 'store']);
    Route::put('/docentes/{id}', [DocenteController::class, 'update']);
    Route::delete('/docentes/{id}', [DocenteController::class, 'destroy']);

    Route::get('/tutores', [TutorController::class, 'index']);
    Route::post('/tutores', [TutorController::class, 'store']);
    Route::put('/tutores/{id}', [TutorController::class, 'update']);
    Route::delete('/tutores/{id}', [TutorController::class, 'destroy']);

    Route::get('/cursos', [CursoController::class, 'index']);
    Route::get('/clases', [ClaseController::class, 'index']);
    Route::get('/mis-clases', [ClaseController::class, 'misClases']);
    Route::get('/clases/{id}/alumnos', [ClaseController::class, 'alumnos']);
    Route::post('/asistencia', [AusenciaController::class, 'storeAsistencia']);
    Route::get('/ausencias/alumno/{alumnoId}', [AusenciaController::class, 'porAlumno']);
});

// Rutas de autenticación por defecto (Laravel Breeze)
require __DIR__.'/auth.php';

?>
