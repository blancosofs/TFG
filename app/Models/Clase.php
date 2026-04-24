<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Clase extends Model
{
    protected $table = 'clases';
    protected $fillable = ['nombre', 'codigo_acceso', 'curso_id'];
}
