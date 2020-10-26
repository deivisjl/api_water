<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoServicio extends Model
{
    protected $table = 'estado_servicio';

    const TRAMITE = '1';
    const VIGENTE = '2';
    const SUSPENDIDO = '3';
    const RECHAZADO = '4';
    
    protected $fillable = [
        'id', 'nombre', 'descripcion','inicia_tramite'
    ];
}
