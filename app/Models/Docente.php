<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Docente extends Model
{
    protected $table = 'docentes';

    protected $fillable = ['telefono', 'asignaturas', 'colegio_id', 'coordinador_id', 'user_id'];

    // Relación 1:1 - Un docente TIENE UN usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relación 1:N
     public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function ausencias()
    {
        return $this->hasMany(Ausencia::class);
    }

    // Relación N:M 
    // Un docente IMPARTE MUCHAS clases (a través de la tabla docentes_clases)
    public function clases()
    {
        // Pasamos el modelo, el nombre de la tabla pivote, y las dos claves
        return $this->belongsToMany(Clase::class, 'docentes_clases', 'docente_id', 'clase_id');
    }
}
