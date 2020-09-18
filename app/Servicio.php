<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
	use SoftDeletes;

	protected $table = "servicio";

	protected $dates = ['deleted_at'];

    protected $fillable = [
    	'id',
		'usuario_id',
		'sector_id',
		'estado_servicio_id',
		'direccion',
		'referencia_direccion',
		'lat',
		'long',
		'fecha_solicitud',
		'fecha_aprobacion',
		'fecha_visita',
		'comite_id',
    ];
}
