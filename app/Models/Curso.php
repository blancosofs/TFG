<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['nombre', 'colegio_id'];

    //Relación 1:N
     public function clases()
    {
        return $this->hasMany(Clase::class);
    }

     public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
