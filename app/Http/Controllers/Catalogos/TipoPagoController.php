<?php

namespace App\Http\Controllers\Catalogos;

use App\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TipoPagoController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/tipo-pago",
    *     summary="Mostrar registros de los tipos de pagos",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los tipos de pagos."
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

        $tipo_pago = DB::table('tipo_pago') 
                ->select('id','nombre','descripcion','monto') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('tipo_pago')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $tipo_pago,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Post(
    *     path="/api/tipo-pago",
    *     summary="Guardar tipos de pagos",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Tipo pago",
    *          description="Datos del nuevo tipo de pago",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos tipos de pagos."
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
                'monto' => 'required|numeric|min:1',
                'descripcion' => 'required|string'
            ];

        $this->validate($request, $rules);

        $tipo = new TipoPago();
        $tipo->nombre = $request->nombre;
        $tipo->monto = $request->monto;
        $tipo->descripcion = $request->descripcion;
        $tipo->save();

        return $this->showOne($tipo);

    }

    /**
    * @SWG\Get(
    *     path="/api/tipo-pago/{id}/edit",
    *     summary="Obtener un tipo especifico",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del tipo de pago a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un tipo de pago especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(TipoPago $tipo_pago)
    {
        return $this->showOne($tipo_pago);
    }

   /**
    * @SWG\PUT(
    *     path="/api/tipo-pago/{tipo_pago}",
    *     summary="Actualizar un tipo de pago especifico",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del tipo de pago a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un tipo de pago especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, TipoPago $tipo_pago)
    {
        $rules = [
                'nombre' => 'required|string',
                'monto' => 'required|numeric|min:1',
                'descripcion' => 'required|string'
            ];

        $this->validate($request, $rules);
        
        $tipo_pago->nombre = $request->nombre;
        $tipo_pago->monto = $request->monto;
        $tipo_pago->descripcion = $request->descripcion;
        if(!$tipo_pago->isDirty())
            return $this->errorResponse('Se debe especificar al menos un valor distinto para actualizar',423);
        else
            $tipo_pago->save();

        return $this->showOne($tipo_pago);
    }

    /**
    * @SWG\DELETE(
    *     path="/api/tipo-pago/{id}",
    *     summary="Eliminar un tipo de pago especifico",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del tipo de pago a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un tipo de pago especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(TipoPago $tipo_pago)
    {
        $accion = $tipo_pago;

        $tipo_pago->delete();

        return $this->showOne($accion,201);
    }

    /**
    * @SWG\Get(
    *     path="/api/tipo-pago-obtener",
    *     summary="Mostrar registros de los tipos de pagos",
    *     tags={"Tipo Pago"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los tipos de pagos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function obtener()
    {
        $registros = TipoPago::all();

        return $this->showAll($registros);
    }
}
