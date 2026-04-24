<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Colegio extends Model
{
    protected $fillable = ['nombre', 'entidad', 'direccion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
        ];

}
