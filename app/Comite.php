<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comite extends Model
{
    use SoftDeletes;
    
    protected $table = 'comite';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'nombres','apellidos','puesto'
    ];
}
