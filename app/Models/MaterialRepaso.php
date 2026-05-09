<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRepaso extends Model
{
    use SoftDeletes;

    protected $table = 'materiales_repaso';

    protected $fillable = [
        'docente_id', 'colegio_id', 'titulo', 'descripcion',
        'tipo_contenido', 'archivo_nombre_original', 'archivo_ruta',
        'archivo_tamaño', 'url_externa', 'materia', 'tema', 'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function colegio()
    {
        return $this->belongsTo(Colegio::class);
    }

    public function tutores()
    {
        return $this->belongsToMany(Tutor::class, 'material_repaso_tutor');
    }

    public function scopePublicados($query)
    {
        return $query->where('publicado', true);
    }

    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    public function scopeOrdenadasPorFecha($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getTamañoLegibleAttribute()
    {
        if (!$this->archivo_tamaño) return null;
        $bytes = $this->archivo_tamaño;
        $unidades = ['B', 'KB', 'MB'];
        for ($i = 0; $i < count($unidades) - 1; $i++) {
            if ($bytes < 1024) break;
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $unidades[$i];
    }
}
