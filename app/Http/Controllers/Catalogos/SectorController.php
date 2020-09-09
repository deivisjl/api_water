<?php

namespace App\Http\Controllers\Catalogos;

use App\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SectorController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/sectores",
    *     summary="Mostrar registros de los sectores",
    *     tags={"Sectores"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los sectores."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "nombre";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $sectores = DB::table('sector') 
                ->select('id','nombre','descripcion') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('sector')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $sectores,
        );

        return response()->json($data, 200);
    }
    /**
    * @SWG\Post(
    *     path="/api/sectores",
    *     summary="Guardar sectores",
    *     tags={"Sectores"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Sector",
    *          description="Datos del nuevo sector",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos sectores."
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
                'nombre' => 'required|string',
                'descripcion' => 'required|string'
            ];

        $this->validate($request, $rules);

        $sector = new Sector();
        $sector->nombre = $request->nombre;
        $sector->descripcion = $request->descripcion;
        $sector->save();

        return $this->showOne($sector);
    }
    /**
    * @SWG\Get(
    *     path="/api/sectores/{id}/edit",
    *     summary="Obtener un sector especifico",
    *     tags={"Sectores"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del sector a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un sector especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(Sector $sectore)
    {
        return $this->showOne($sectore);
    }
    /**
    * @SWG\PUT(
    *     path="/api/sectores/{sector}",
    *     summary="Actualizar un sector especifico",
    *     tags={"Sectores"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del sector a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un sector especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Sector $sectore)
    {
        $rules = [
                'nombre' => 'required|string',
                'descripcion' => 'required|string'
            ];

        $this->validate($request, $rules);

        $sectore->nombre = $request->nombre;
        $sectore->descripcion = $request->descripcion;
        if(!$sectore->isDirty())
            return $this->errorResponse('Se debe especificar al menos un valor distinto para actualizar',423);
        else
            $sectore->save();

        return $this->showOne($sectore);
    }

    /**
    * @SWG\DELETE(
    *     path="/api/sectores/{id}",
    *     summary="Eliminar un sector especifico",
    *     tags={"Sectores"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del sector a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un sector especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(Sector $sectore)
    {
        $accion = $sectore;

        $sectore->delete();

        return $this->showOne($accion,201);

    }

    public function sectores()
    {
        $registro = Sector::all();

        return $this->showAll($registro);
    }
}
