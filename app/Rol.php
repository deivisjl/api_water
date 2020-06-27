<?php

namespace App\Http\Controllers\Acceso;

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
}
