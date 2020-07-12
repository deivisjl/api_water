<?php

namespace App\Http\Controllers\Acceso;

use App\User;
use App\Permiso;
use App\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class UsuarioRolController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/usuario-menu",
    *     summary="Listar los menus que tiene habilitado el rol",
    *     tags={"Menu"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los items del menu."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function menu(Request $request)
    {
        $user = $request->user();

        // $permisos = DB::table('permiso')                        
        //                 ->get();


        // $aux = $permisos;

        // $menu = [];
        // $submenu =[];

        // foreach ($permisos as $index => $permiso) 
        // {
        //     if($permiso->menu_titulo_id == 0)
        //     {
        //         $menu[$index] = $permiso;

        //         foreach ($aux as $key => $p) 
        //         {
        //             if($p->menu_titulo_id == $permiso->id)
        //             {
        //                 array_push($submenu,$p);
        //             }
        //         }

        //         $x = array('submenu'=>$submenu);
        //     }    
        // }

        // $menus = collect($menu);

        $menus = $user->usuario_rol()
                        ->with('rol.permiso_rol.permiso.subgrupo')
                        ->get()
                        ->pluck('rol.permiso_rol')
                        ->collapse()
                        ->pluck('permiso')
                        ->where('menu_titulo_id',0)
                        ->values();


        return $this->showAll($menus);
    }
}
