<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Coordinador extends Model
{
    protected $table = 'coordinadores';
    protected $fillable = ['colegio_id', 'user_id'];
}
