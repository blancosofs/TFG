<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['nombre', 'apellidos', 'colegio_id', 'curso_id', 'clase_id', 'activo'])]

class Alumno extends Model
{
    //
}
