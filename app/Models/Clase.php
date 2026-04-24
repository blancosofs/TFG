<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['nombre', 'codigo_acceso', 'curso_id'])]
class Clase extends Model
{
    //
}
