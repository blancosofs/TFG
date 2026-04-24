<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Alumno extends Model
{
        protected $table = 'alumnos';
        protected $fillable = ['nombre', 'apellidos', 'colegio_id', 'curso_id', 'clases_id', 'activo'];

        protected $casts = [
        'activo' => 'boolean',
        ];

}
