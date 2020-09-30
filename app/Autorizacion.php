<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected $table = 'autorizacion';

    protected $fillable = [
       	'id','nombre_comite','municipio_departamento','autorizacion','registro_contraloria','fecha','activo'
    ];
}
