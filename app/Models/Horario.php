<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $fillable = ['dia_semana', 'hora_inicio', 'hora_fin', 'docente_id', 'clase_id'];



}
