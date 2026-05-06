<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioTablon extends Model
{
    protected $table = 'comentarios_tablon';
    protected $fillable = ['tablon_id', 'user_id', 'texto'];

    public function tablon()
    {
        return $this->belongsTo(Tablon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
