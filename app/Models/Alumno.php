<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Alumno extends Model
{
        protected $table = 'alumnos';
        protected $fillable = ['nombre', 'apellidos', 'colegio_id', 'curso_id', 'clases_id', 'activo'];

        protected $casts = [
        'activo' => 'boolean',
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
