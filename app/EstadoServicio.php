<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoServicio extends Model
{
    protected $table = 'estado_servicio';

    protected $fillable = [
        'id', 'nombre', 'descripcion',
    ];
}
