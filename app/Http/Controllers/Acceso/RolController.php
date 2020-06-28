<?php

namespace App\Http\Controllers\Acceso;

use App\Rol;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
    
class RolController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/roles",
    *     summary="Mostrar roles",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los roles."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index()
    {
        $roles = Rol::all();

        return $this->showAll($roles);
    }

    /**
    * @SWG\Post(
    *     path="/api/roles",
    *     summary="Guardar nuevos roles",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos roles."
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
    *     path="/api/roles/{id}",
    *     summary="Mostrar un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},      
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function show(Rol $rol)
    {
        //
    }
    /**
    * @SWG\PUT(
    *     path="/api/roles/{rol}",
    *     summary="Actualizar un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Rol $rol)
    {
        //
    }

    /**
    * @SWG\DELETE(
    *     path="/api/roles/{id}",
    *     summary="Eliminar un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(Rol $rol)
    {
        //
    }
}
