<?php

namespace App\Http\Controllers\Acceso;

use App\Permiso;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PermisoController extends ApiController
{
     /**
    * @SWG\Get(
    *     path="/api/permisos",
    *     summary="Mostrar permisos",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los permisos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index()
    {
        
    }

    /**
    * @SWG\Post(
    *     path="/api/permisos",
    *     summary="Guardar nuevos permisos",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos permisos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function store(Request $request)
    {
        //
    }

    /**
    * @SWG\Get(
    *     path="/api/permisos/{id}",
    *     summary="Mostrar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},      
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar un permiso especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function show(Permiso $permiso)
    {
        //
    }
    /**
    * @SWG\PUT(
    *     path="/api/permisos/{permiso}",
    *     summary="Actualizar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un permiso especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Permiso $permiso)
    {
        //
    }

    /**
    * @SWG\DELETE(
    *     path="/api/permisos/{id}",
    *     summary="Eliminar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un permiso especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(Permiso $permiso)
    {
        //
    }
}
