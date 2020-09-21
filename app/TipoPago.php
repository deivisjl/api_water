<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table = 'tipo_pago';

    protected $fillable = [
        'id', 'nombre', 'monto','descripcion','unico'
    ];
}
