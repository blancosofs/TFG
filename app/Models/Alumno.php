<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Alumno extends Model
{
        protected $table = 'alumnos';
        protected $fillable = ['nombre', 'apellidos', 'fecha_nacimiento', 'colegio_id', 'curso_id', 'clase_id', 'activo'];

        protected $casts = [
            'activo' => 'boolean',
            'fecha_nacimiento' => 'date:Y-m-d' // <-- Añadido
        ];

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    // Relación 1:1 - Un alumno PERTENECE A una clase
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    // Relación 1:N - Un alumno PERTENECE A un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    
    //Relación 1:N
    public function ausencias()
    {
        return $this->hasMany(Ausencia::class);
    }

    // Relación N:M -  Un alumno TIENE muchos tutores legales
    public function tutores()
    {
        return $this->belongsToMany(Tutor::class, 'tutores_alumnos', 'alumno_id', 'tutor_id')
                    ->withPivot('parentesco'); // ¡Igual aquí!
    }

}
