<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController; //para el formulario de unete

//controladores
//Route::post('/unete', [SolicitudController::class, 'enviar'])->name('solicitud.enviar'); //aqui se maneja el formulario de unete, se llama al metodo enviar del controlador SolicitudController


//html
Route::view('/', 'PaginaInicio')->name('index');
Route::view('/contacto', 'PaginaContacto')->name('contacto');
Route::view('/unete', 'PaginaUnete')->name('unete');

//Route::view('/login', 'login')->name('login');
Route::view('/configuracion', 'configuracion')->name('config');


Route::view('/perfil', 'perfil')->name('perfil');
Route::view('/perfil/admin', 'perfilAdmin')->name('perfil.admin');
Route::view('/perfil/familia', 'perfilFamilia')->name('perfil.familia');
Route::view('/perfil/profesor', 'perfilProfesor')->name('perfil.profesor');


Route::view('/calendario', 'calendario')->name('calendario');

Route::view('/admin-panel', 'admin')->middleware('auth')->name('admin');
//Route::view('/admin-panel', 'admin')->name('admin.panel');


// Ruta para el formulario de Contacto (Público)
Route::post('/contacto-enviar', [App\Http\Controllers\ContactoController::class, 'enviarConsulta'])->name('contacto.enviar');

// Ruta para el formulario de Únete (Solo tú como Admin)
Route::middleware(['auth'])->group(function () {
    Route::post('/registrar-colegio', [App\Http\Controllers\AdminController::class, 'registrarCentro'])->name('solicitud.enviar');
});

require __DIR__.'/auth.php';
