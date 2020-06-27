<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';

    protected $fillable = ['id','menu_titulo_id','titulo','icono','ruta_cliente','ruta_api','visibilidad','descripcion'];
}
