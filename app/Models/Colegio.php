<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Colegio extends Model
{
    protected $table = 'colegios';

    protected $fillable = ['nombre', 'entidad', 'direccion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    //Relación 1:1 - Un colegio tiene un coordinador!
    public function coordinador()
    {
        return $this->belongsTo(Coordinador::class);
    }

    //Relación 1:N - Un colegio tiene varios ... !
    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function docentes()
    {
        return $this->hasMany(Docente::class);
    }

     public function cursos()
    {
        return $this->hasMany(Curso::class);
    }


}
