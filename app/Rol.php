<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Rol extends Model
{
    use Sluggable;
    
    protected $table = 'rol';

    protected $fillable = ['id','nombre','slug','descripcion'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'nombre'
            ]
        ];
    }

    public function usuario_rol(){
        return $this->hasMany(UsuarioRol::class);
    }

    public function permiso_rol(){
        return $this->hasMany(PermisoRol::class);
    }
}
