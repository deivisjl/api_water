<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anio extends Model
{
    protected $table = 'anio';

    protected $fillable = [
        'id', 'nombre'
    ];
}
