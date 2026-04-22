<?php

// routes/api.php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/colegios', [AdminController::class, 'store']);
// Para la lista que él intenta cargar al principio
Route::get('/admin/colegios', [AdminController::class, 'index']);
