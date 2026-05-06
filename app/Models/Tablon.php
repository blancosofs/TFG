<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tablon extends Model
{
    protected $table = 'tablon';
    protected $fillable = [
        'docente_id', 'tutor_id', 'titulo', 'categoria',
        'dirigido_a', 'contenido', 'clase_id', 'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioTablon::class);
    }
}
