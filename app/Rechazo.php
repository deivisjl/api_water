<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rechazo extends Model
{
    protected $table = 'rechazo';

    protected $fillable = [
        'id', 'solicitud_id','motivo'
    ];
}
