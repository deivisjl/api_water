<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $table = 'telefono';

    protected $fillable = ['id','usuario_id','numero'];

    public function usuario()
    {
    	return $this->belongsTo(User::class,'usuario_id');
    }
}
