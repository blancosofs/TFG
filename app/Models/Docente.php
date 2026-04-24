<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Docente extends Model
{
    protected $table = 'docentes';
    protected $fillable = ['telefono', 'colegio_id', 'coordinador_id', 'user_id'];
}
