<?php

namespace App\Imports;

use App\Rol;
use App\User;
use App\Permiso;
use App\PermisoRol;
use App\UsuarioRol;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MenuImport implements ToCollection
{
	private $permisos = array();
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
        	$permiso = new Permiso();
        	$permiso->menu_titulo_id = $row[1];
        	$permiso->titulo = $row[2];
        	$permiso->icono = $row[4];
        	$permiso->ruta_cliente = '/'.$row[3];
        	$permiso->visibilidad = $row[5];
        	$permiso->descripcion = $row[8];
        	$permiso->orden = $row[7];
        	$permiso->save();

        	$this->permisos[] = [$row[0], $row[6]];//almacenar el id y el nombre del rol
        	
        	echo "\e[39m".'Rol: '."\e[32m".$row[6]."\e[39m".' --Permiso: '."\e[32m".$row[2]."\e[39m".' --Ruta: '."\e[32m".$row[3]. PHP_EOL;
        }

        $this->asignar_rol();//asignar los permisos a los roles

        $this->usuario_roles();//asignar todos los roles al usuario por defecto
    }

    public function asignar_rol()
    {
    	$admin = 'Administrador';
    	$digitador = 'Digitador';

    	foreach ($this->permisos as $value) 
    	{
    		switch($value[1]) //seleccionar el nombre del rol
    		{
    				case $admin:
    					$this->permiso_rol($admin,$value[0]);
    				break;

    				case $digitador:
    					$this->permiso_rol($digitador,$value[0]);
    				break;
    				default:
    				break;
    		}
    	}
    }

    public function permiso_rol($rol, $permiso)
    {
    		$rol = Rol::select('id')->where('nombre','=',$rol)->first();

			PermisoRol::create([
                'permiso_id' => $permiso,
                'rol_id' => $rol->id
            ]);
    }

    public function usuario_roles()
    {
    	$usuario = User::first();

    	$roles = Rol::all();

    	foreach ($roles as $rol) 
    	{
    		UsuarioRol::create([
	            'rol_id' => $rol->id,
	            'usuario_id' => $usuario->id
	        ]);
    	}
    }
}
