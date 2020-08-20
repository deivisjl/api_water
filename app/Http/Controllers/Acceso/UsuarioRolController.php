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

        $permisos = DB::table('rol as r')
                    ->join('usuario_rol as ur','ur.rol_id','=','r.id')
                    ->join('permiso_rol as pr','pr.rol_id','=','r.id')
                    ->join('permiso as p','p.id','pr.permiso_id')
                    ->select('p.id','p.menu_titulo_id','p.titulo','p.icono','p.ruta_cliente','p.visibilidad','p.orden')
                    ->where('ur.usuario_id','=', $user->id)
                    ->get();


        $menus = collect($permisos);

        return $this->showAll($menus);
    }
}
