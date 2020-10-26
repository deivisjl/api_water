<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table = 'tipo_pago';

    const PAGO_INSTALACION = '1';
    const CANON_DE_AGUA = '2';

    protected $fillable = [
        'id', 'nombre', 'monto','descripcion','unico'
    ];

    public function pago()
    {
    	return $this->hasMany('App\Pago');
    }
}
