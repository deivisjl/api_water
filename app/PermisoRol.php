<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisoRol extends Model
{
    protected $table = 'permiso_rol';
    
    protected $fillable = ['id','permiso_id','rol_id'];

    public function permiso()
    {
    	return $this->belongsTo(Permiso::class);
    }

    public function rol()
    {
    	return $this->belongsTo(Rol::class);
    }
}
