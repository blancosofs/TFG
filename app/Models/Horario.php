<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $fillable = ['dia_semana', 'hora_inicio', 'hora_fin', 'docente_id', 'clase_id', 'asignatura'];

    protected $casts = [];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    //Relación 1:N
     public function ausencias()
    {
        return $this->hasMany(Ausencia::class);
    }
}
