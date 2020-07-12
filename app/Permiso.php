<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';

    protected $fillable = ['id','menu_titulo_id','titulo','icono','ruta_cliente','ruta_api','visibilidad','descripcion'];

    public function permiso_rol()
    {
    	return $this->hasMany(PermisoRol::class);
    }

    public function menu_titulo()
    {
    	return $this->belongsTo(Permiso::class,'menu_titulo_id','id');
    }

    public function subgrupo()
    {
    	return $this->hasMany(Permiso::class,'menu_titulo_id','id');
    }
}
