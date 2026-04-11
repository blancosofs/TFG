<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'PaginaInicio')->name('index');
Route::view('/contacto', 'PaginaContacto')->name('contacto');
Route::view('/unete', 'PaginaUnete')->name('unete');
Route::view('/login', 'login')->name('login');
Route::view('/configuracion', 'configuracion')->name('config');
