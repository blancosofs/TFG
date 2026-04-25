<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitudController; //Para el formulario de unete
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\TutorController; 

//controladores
//Route::post('/unete', [SolicitudController::class, 'enviar'])->name('solicitud.enviar'); //aqui se maneja el formulario de unete, se llama al metodo enviar del controlador SolicitudController

//Como se importan todo de una sentada pues es más facild manejar

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS Y VISTAS ESTÁTICAS 
|--------------------------------------------------------------------------
*/
Route::view('/', 'PaginaInicio')->name('index');
Route::view('/contacto', 'PaginaContacto')->name('contacto');
Route::view('/unete', 'PaginaUnete')->name('unete');

// Formulario de Contacto (Público)
Route::post('/contacto-enviar', [ContactoController::class, 'enviarConsulta'])->name('contacto.enviar');


/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Solo usuarios logueados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- TUS VISTAS DE PERFIL Y CONFIGURACIÓN ---
    Route::view('/configuracion', 'configuracion')->name('config');
    Route::view('/perfil', 'perfil')->name('perfil');
    Route::view('/perfil/admin', 'perfilAdmin')->name('perfil.admin');
    Route::view('/perfil/familia', 'perfilFamilia')->name('perfil.familia');
    Route::view('/perfil/profesor', 'perfilProfesor')->name('perfil.profesor');
    Route::view('/calendario', 'calendario')->name('calendario');
    Route::view('/admin-panel', 'admin')->name('admin');

    // --- TU RUTA DE ADMINISTRADOR ---
    Route::post('/registrar-colegio', [AdminController::class, 'registrarCentro'])->name('solicitud.enviar');

    /*
    |--------------------------------------------------------------------------
    | 2.a RUTAS DEL SISTEMA CRUD 
    |--------------------------------------------------------------------------
    */

    // 1. PERFIL: DESARROLLADOR / ADMIN GLOBAL
    Route::prefix('admin')->group(function () {
        Route::resource('colegios', ColegioController::class);
        Route::resource('coordinadores', CoordinadorController::class);
    });

    // 2. PERFIL: COORDINADOR
    Route::prefix('coordinacion')->group(function () {
        Route::resource('cursos', CursoController::class);
        Route::resource('clases', ClaseController::class);
        Route::resource('horarios', HorarioController::class);
        Route::resource('alumnos', AlumnoController::class);
        Route::resource('docentes', DocenteController::class);
        Route::resource('tutores', TutorController::class);
        Route::resource('ausencias', AusenciaController::class);
    });

    // 3. PERFIL: DOCENTE
    Route::prefix('profesor')->group(function () {
        Route::resource('mis-clases', ClaseController::class)->only(['index', 'show']);
        Route::resource('alumnos', AlumnoController::class)->only(['show']);
        Route::resource('tutores', TutorController::class)->only(['show']);
        Route::resource('horarios', HorarioController::class)->only(['index']);
        Route::resource('ausencias', AusenciaController::class)->only(['create', 'store', 'index']);
    });

    // 4. PERFIL: TUTOR LEGAL (Familia)
    Route::prefix('familia')->group(function () {
        Route::resource('mis-hijos', AlumnoController::class)->only(['index', 'show']);
        Route::resource('profesores', DocenteController::class)->only(['index', 'show']);
        Route::resource('ausencias', AusenciaController::class)->only(['index', 'create', 'store', 'update', 'edit']);
    });

});

// Rutas de autenticación por defecto (Laravel Breeze)
require __DIR__.'/auth.php';

?>
