<?php

namespace App\Http\Controllers\Catalogos;

use App\EstadoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class EstadoServicioController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/estado-permiso",
    *     summary="Mostrar registros de estados de servicios",
    *     tags={"Estado Servicio"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los estados de servicios."
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

        $estado_servicio = DB::table('estado_servicio') 
                ->select('id','nombre','descripcion') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('estado_servicio')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $estado_servicio,
        );

        return response()->json($data, 200);
    }
    /**
    * @SWG\Post(
    *     path="/api/estado-servicio",
    *     summary="Guardar nuevos estados de servicios",
    *     tags={"Estado Servicio"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del nuevo estado servicio",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos estados servicios."
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

        $estado = new EstadoServicio();
        $estado->nombre = $request->nombre;
        $estado->descripcion = $request->descripcion;
        $estado->save();

        return $this->showOne($estado);
    }
    /**
    * @SWG\Get(
    *     path="/api/estado-servicio/{id}/edit",
    *     summary="Obtener un estado especifico",
    *     tags={"Estado Servicio"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del estado a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un estado especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(EstadoServicio $estado_servicio)
    {
        return $this->showOne($estado_servicio);
    }

    /**
    * @SWG\PUT(
    *     path="/api/estado-servicio/{estado}",
    *     summary="Actualizar un estado especifico",
    *     tags={"Estado Servicio"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del estado a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un estado especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, EstadoServicio $estado_servicio)
    {
        $rules = [
                'nombre' => 'required|string',
                'descripcion' => 'required|string'
            ];

        $this->validate($request, $rules);

        $estado_servicio->nombre = $request->nombre;
        $estado_servicio->descripcion = $request->descripcion;
        
        if(!$estado_servicio->isDirty())
            return $this->errorResponse('Se debe especificar al menos un valor distinto para actualizar',423);
        else
            $estado_servicio->save();

        return $this->showOne($estado_servicio);
    }

    /**
    * @SWG\DELETE(
    *     path="/api/estado-servicio/{id}",
    *     summary="Eliminar un estado especifico",
    *     tags={"Estado Servicio"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del estado a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un estado especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(EstadoServicio $estado_servicio)
    {
        $accion = $estado_servicio;

        $estado_servicio->delete();

        return $this->showOne($accion,201);

    }
}
