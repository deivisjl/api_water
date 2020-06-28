<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table = 'usuario_rol';

    protected $fillable = ['id','rol_id','usuario_id'];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function rol(){
        return $this->belongsTo(Rol::class);
    }
}
