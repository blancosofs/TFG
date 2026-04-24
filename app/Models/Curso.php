<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'colegio_id'];
}
