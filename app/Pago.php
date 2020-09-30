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

    public function mes()
    {
    	return $this->belongsTo('App\Mes');
    }

    public function servicio()
    {
    	return $this->belongsTo('App\Servicio');
    }

    public function tipo_pago()
    {
    	return $this->belongsTo('App\TipoPago');
    }
}
