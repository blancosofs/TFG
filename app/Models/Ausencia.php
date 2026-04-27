<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Ausencia extends Model
{
    protected $table = 'ausencias';
    protected $fillable = ['fecha', 'tipo', 'justificada', 'justificacion', 'alumno_id', 'docente_id', 'horario_id'];  

    protected $casts = [
        'justificada' => 'boolean',
        'fecha' => 'date'
        ];


}
