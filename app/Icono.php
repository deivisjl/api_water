<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icono extends Model
{
    protected $table = 'icono';

    protected $fillable = [
        'id', 'nombre'
    ];
}
