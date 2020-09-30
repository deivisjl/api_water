<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    protected $table = 'mes';

    protected $fillable = [
        'id', 'nombre'
    ];

    public function pago()
    {
    	return $this->hasMany(Pago::class);
    }
}
