<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = ['nombre', 'codigo_acceso', 'curso_id'];

    //Relación 1:N
     public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

     public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    // Relación N:M - Una clase RECIBE A MUCHOS docentes
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docentes_clases', 'clase_id', 'docente_id');
    }

     // Relación 1:N - Una clase PERTENECE A un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
   
}
