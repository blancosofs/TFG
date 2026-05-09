<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Colegio extends Model
{
    protected $table = 'colegios';

    protected $fillable = [
        'nombre', 'entidad', 'direccion', 'activo',
        'tipo', 'etapas', 'calle', 'ciudad', 'comunidad',
        'cp', 'telefono', 'email', 'web', 'num_alumnos', 'notas',
    ];

    protected $casts = ['activo' => 'boolean'];

    // Un colegio tiene un coordinador (coordinadores.colegio_id)
    public function coordinador()
    {
        return $this->hasOne(Coordinador::class);
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
