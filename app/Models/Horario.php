<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['dia_semana', 'hora_inicio', 'hora_fin', 'docente_id', 'clase_id'])]

class Horario extends Model
{
    //
}
