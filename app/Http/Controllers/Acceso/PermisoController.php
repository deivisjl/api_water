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
        $permisos = Permiso::all();

        return $this->showAll($permisos);
    }

    /**
    * @SWG\Post(
    *     path="/api/permisos",
    *     summary="Guardar nuevos permisos",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Permiso",
    *          description="Datos del nuevo permiso",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
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
        $rules = [
                'padre' => 'nullable|numeric',
                'titulo' => 'required|string',
                'icono' => 'required|string',
                'ruta' => 'required|string',
                'visible' => 'required|boolean',
                'descripcion' => 'required|string',
                'orden' => 'required|numeric'
            ];

        $this->validate($request, $rules);

        $permiso = new Permiso();
        $permiso->menu_titulo_id = empty($request->padre) ? 0 : $request->padre;
        $permiso->titulo = $request->titulo;
        $permiso->icono = $request->icono;
        $permiso->ruta_cliente = $request->ruta;
        $permiso->visibilidad = $request->visible ? 'visible' : 'oculto';
        $permiso->descripcion = $request->descripcion;
        $permiso->orden = $request->orden;
        $permiso->save();

        return $this->showOne($permiso);
    }

    /**
    * @SWG\Get(
    *     path="/api/permisos/{id}",
    *     summary="Mostrar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del permiso a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
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
    public function edit(Permiso $permiso)
    {
        return $this->showOne($permiso);
    }
    /**
    * @SWG\PUT(
    *     path="/api/permisos/{permiso}",
    *     summary="Actualizar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Permiso",
    *          description="Datos del permiso a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
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
        $rules = [
                'padre' => 'nullable|numeric',
                'titulo' => 'required|string',
                'icono' => 'required|string',
                'ruta' => 'required|string',
                'visible' => 'required|boolean',
                'descripcion' => 'required|string',
                'orden' => 'required|numeric'
            ];

        $this->validate($request, $rules);

        $permiso->menu_titulo_id = empty($request->padre) ? 0 : $request->padre;
        $permiso->titulo = $request->titulo;
        $permiso->icono = $request->icono;
        $permiso->ruta_cliente = $request->ruta;
        $permiso->visibilidad = $request->visible ? 'visible' : 'oculto';
        $permiso->descripcion = $request->descripcion;
        $permiso->orden = $request->orden;

        if(!$permiso->isDirty())
            return $this->errorResponse('Se debe especificar al menos un valor distinto para actualizar',423);
        else
            $permiso->save();

        return $this->showOne($permiso,201);
    }

    /**
    * @SWG\DELETE(
    *     path="/api/permisos/{id}",
    *     summary="Eliminar un permiso especifico",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del permiso a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
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
        $accion = $permiso;

        $permiso->delete();

        return $this->showOne($accion,201);
    }
    /**
    * @SWG\Get(
    *     path="/api/padres",
    *     summary="Mostrar los permisos que son padres",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los permisos que son padres."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function padre()
    {
        $permisos = Permiso::where('menu_titulo_id',0)
                            ->orderBy('titulo','asc')
                            ->get();

        return $this->showAll($permisos);
    }

    /**
    * @SWG\Get(
    *     path="/api/permisos-nombre",
    *     summary="Mostrar los permisos por nombre",
    *     tags={"Permisos"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los permisos por nombre."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function titulo()
    {
        $permisos = Permiso::orderBy('titulo','asc')
                            ->get(['id','titulo','descripcion']);

        return $this->showAll($permisos);
    }
}
