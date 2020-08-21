<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sector';

    protected $fillable = [
        'id', 'nombre', 'descripcion',
    ];
}
