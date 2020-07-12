<?php

use App\Rol;
use App\User;
use App\Permiso;
use App\PermisoRol;
use App\UsuarioRol;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Primer titulo de menu [ACCESOS]
        $menu_1 = Permiso::create([
            'titulo' => 'Accesos',
            'icono' => 'lock',
            'ruta_cliente' => '/#',
            'visibilidad' => 'visible',
            'descripcion' => 'Titulo principal de menu de acceso',
            'orden' => 1
        ]);

        //Primer submenu [PERMISOS] de menu [ACCESOS]    
        $permiso_1 = Permiso::create([
            'menu_titulo_id' => $menu_1->id,
            'titulo' => 'Permisos',
            'icono' => 'account_tree',
            'ruta_cliente' => '/permisos',
            'ruta_api' => 'api/permisos',
            'visibilidad' => 'visible',
            'descripcion' => 'Submenu para permisos',
            'orden' => 1
        ]);

        //Segundo submenu [ROLES] de menu [ACCESOS]    
        $permiso_2 = Permiso::create([
            'menu_titulo_id' => $menu_1->id,
            'titulo' => 'Roles',
            'icono' => 'all_inbox',
            'ruta_cliente' => '/roles',
            'ruta_api' => 'api/roles',
            'visibilidad' => 'visible',
            'descripcion' => 'Submenu para roles',
            'orden' => 2
        ]);

        //Primer submenu [USUARIOS] de menu [ACCESOS]    
        $permiso_3 = Permiso::create([
            'menu_titulo_id' => $menu_1->id,
            'titulo' => 'Asignar roles',
            'icono' => 'account_box',
            'ruta_cliente' => '/rol-usuario',
            'ruta_api' => 'api/rol-usuario',
            'visibilidad' => 'visible',
            'descripcion' => 'Submenu para asignar roles a usuarios',
            'orden' => 3
        ]);
        
        $permisos = array($menu_1, $permiso_1, $permiso_2, $permiso_3);
        //Asignación de roles por defecto
        $rol = Rol::where('nombre','Administrador')->first();

        if($rol)
        {
            foreach ($permisos as $key => $permiso) {
                PermisoRol::create([
                    'permiso_id' => $permiso->id,
                    'rol_id' => $rol->id
                ]);    
            }
        }

        /* Asignación de rol por defecto */
        $usuario = User::first();

        UsuarioRol::create([
            'rol_id' => $rol->id,
            'usuario_id' => $usuario->id
        ]);
    }
}
