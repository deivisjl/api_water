<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisoRol extends Model
{
    protected $table = 'permiso_rol';
    
    protected $fillable = ['id','permiso_id','rol_id'];
}
