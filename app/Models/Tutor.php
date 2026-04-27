<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Tutor extends Model
{
    protected $table = 'tutores';
    protected $fillable = ['telefono', 'user_id'];
    
    // Relación N:M - Un tutor ESTÁ A CARGO DE muchos alumnos (hijos)
    public function alumnos()
    {
        // Pasamos: Modelo destino, nombre de la tabla pivote, clave local, clave destino
        return $this->belongsToMany(Alumno::class, 'tutores_alumnos', 'tutor_id', 'alumno_id')
                    ->withPivot('parentesco'); // ¡El truco para leer si es Padre/Madre!
    }
}
