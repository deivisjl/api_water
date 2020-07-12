<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const USUARIO_ADMINISTRADOR = 'Administrador';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usuario_rol(){

        return $this->hasMany(UsuarioRol::class,'usuario_id');
    }

    public function esAdministrador(){
        
        $rol = DB::table('usuario_rol')
                ->join('rol','usuario_rol.rol_id','=','rol.id')
                ->select('rol.nombre')
                ->where('usuario_rol.usuario_id','=',$this->id)
                ->where('rol.nombre','=', User::USUARIO_ADMINISTRADOR)
                ->first();
                
        return !empty($rol) ? true : false;        
    }
}
