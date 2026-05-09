<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Formularios\ContactoController;
use App\Http\Controllers\toDatabase\AdminController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TutorController; 
use App\Http\Controllers\TablonController;
use App\Http\Controllers\MaterialRepasoController;
use App\Http\Controllers\TutorMaterialController;

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
    // Tablón (accesible a todos los roles autenticados)
    Route::view('/tablon', 'tablon')->name('tablon');

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

        // Material de Repaso (docente)
        Route::resource('material-repaso', MaterialRepasoController::class);
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

        // Material de Repaso (tutor)
        Route::get('tutor/materiales', [TutorMaterialController::class, 'index'])->name('tutor.materiales.index');
        Route::get('tutor/materiales/{materialRepaso}', [TutorMaterialController::class, 'show'])->name('tutor.materiales.show');
        Route::get('tutor/materiales/{materialRepaso}/descargar', [TutorMaterialController::class, 'descargar'])->name('tutor.materiales.descargar');
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

    Route::post('/tablon', [TablonController::class, 'store']);
    Route::put('/tablon/{tablon}', [TablonController::class, 'update']);
    Route::delete('/tablon/{tablon}', [TablonController::class, 'destroy']);
    Route::post('/tablon/{tablon}/comentarios', [TablonController::class, 'storeComentario']);
    Route::delete('/comentarios/{comentario}', [TablonController::class, 'destroyComentario']);


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

    Route::get('/me', [\App\Http\Controllers\ProfileController::class, 'me']);

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
    Route::get('/clases/{id}/alumnos', [ClaseController::class, 'visualizarAlumnos']);
    Route::post('/asistencia', [AusenciaController::class, 'storeAsistencia']);
    Route::get('/ausencias/alumno/{alumnoId}', [AusenciaController::class, 'porAlumno']);

    Route::get('/tutor/alumnos', [TutorController::class, 'misAlumnos']);
    Route::put('/me/datos',      [\App\Http\Controllers\ProfileController::class, 'actualizarDatos']);
    Route::put('/me/password',   [\App\Http\Controllers\ProfileController::class, 'actualizarPassword']);

    Route::get('/tablon', [TablonController::class, 'apiIndex']);

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/colegios',  [ColegioController::class, 'index']);
        Route::post('/admin/colegios', [ColegioController::class, 'store']);
        Route::post('/admin/colegios/{id}/coordinador', [CoordinadorController::class, 'storeForColegio']);
    });
});

// Rutas de autenticación por defecto (Laravel Breeze)
require __DIR__.'/auth.php';
