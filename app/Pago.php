<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
	protected $table = 'pago';

    protected $fillable = [
            'id',
			'mes_id',
			'servicio_id',
			'tipo_pago_id',
			'monto',
			'no_boleta',
			'anio_id'
    ];

}
