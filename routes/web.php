<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
Route::view('/admin-panel', 'admin')->name('admin.panel');

require __DIR__.'/auth.php';
