<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['nombre', 'apellidos', 'email', 'password', 'colegio_id', 'activo'])]


class Tutor extends Model
{
    //
}
