<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Tutor extends Model
{
    protected $table = 'tutores';
    protected $fillable = ['telefono', 'user_id'];
    
}
