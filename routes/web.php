<?php

//use App\Http\Controllers\ProfileController;
//use App\Http\Controllers\SolicitudController; //Para el formulario de unete
use App\Http\Controllers\Formularios\ContactoController; //pones aqui la ruta completa y done
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

    // Vistas comunes para cualquiera que esté logueado
    Route::view('/configuracion.html', 'configuracion')->name('config');

    // ZONA DOCENTES
    Route::middleware(['role:docente'])->group(function () {
        // Vistas
        Route::view('/calendario.html', 'calendario')->name('calendario');
        Route::view('/perfilDocente.html', 'perfilProfesor')->name('perfil');
        Route::view('/pasarLista.html', 'pasarLista');
        
        // CRUD de Docentes
        Route::prefix('profesor')->as('profesor.')->group(function () {
            Route::resource('mis-clases', ClaseController::class)->only(['index', 'show']);
            Route::resource('alumnos', AlumnoController::class)->only(['show']);
            Route::resource('tutores', TutorController::class)->only(['show']);
            Route::resource('horarios', HorarioController::class)->only(['index']);
            Route::resource('ausencias', AusenciaController::class)->only(['create', 'store', 'index']);
        });
    });

    // 🔒 ZONA COORDINADORES
    Route::middleware(['role:coordinador'])->group(function () {
        // Vistas
        Route::view('/dashboard.html', 'coordinador')->name('coordinador');
        Route::view('/perfilCoordinador.html', 'perfilCoordinador')->name('perfilCoordinador');

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
        Route::view('/familiar.html', 'perfilFamilia');
        Route::view('/perfilFamilia.html', 'perfilFamilia')->name('perfilFamilia');

        // CRUD de Familias
        Route::prefix('familia')->group(function () {
            Route::resource('mis-hijos', AlumnoController::class)->only(['index', 'show']);
            Route::resource('profesores', DocenteController::class)->only(['index', 'show']);
            Route::resource('ausencias', AusenciaController::class)->only(['index', 'create', 'store', 'update', 'edit']);
        });
    });

    // ZONA ADMIN GLOBAL
    Route::middleware(['role:admin'])->group(function () {
        // Vistas
        Route::view('/admin.html', 'admin')->name('admin');
        Route::view('/perfilAdmin.html', 'perfilAdmin')->name('perfilAdmin');

        // CRUD de Admin
        Route::prefix('admin')->group(function () {
            Route::resource('colegios', ColegioController::class);
            Route::resource('coordinadores', CoordinadorController::class);
        });
    });
});

// Rutas de autenticación por defecto (Laravel Breeze)
require __DIR__.'/auth.php';

?>
