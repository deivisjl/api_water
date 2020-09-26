<?php

namespace App\Http\Controllers\Pago;

use App\Pago;
use App\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PagoController extends ApiController
{
    /**
    * @SWG\Post(
    *     path="/api/pagos",
    *     summary="Guardar nuevos pagos",
    *     tags={"Pagos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="pago",
    *          description="Datos del nuevo pago",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos pagos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function pagar(Request $request)
    {

        $rules = [
                'usuario'=>'required|numeric',
                'anio'=>'required|numeric',
                'mes'=>'nullable|numeric',
                'monto'=>'required|numeric',
                'servicio'=>'required|numeric',
                'tipo_pago'=>'required|numeric'
            ];

        $this->validate($request, $rules);

        if($this->comprobarPagoUnico($request))
        {
            return $this->errorResponse('Este pago ya existe en los registros',423);
        }

        if($this->comprobarPagoMensual($request))
        {
            return $this->errorResponse('Este pago ya existe en los registros',423);
        }

        $registro = new Pago();
        $registro->anio_id = $request->anio;
        $registro->mes_id = $request->mes;
        $registro->monto = $request->monto;
        $registro->servicio_id = $request->servicio;
        $registro->tipo_pago_id = $request->tipo_pago;
        $registro->save();

        return $this->showOne($registro);
    }

    /**
    * @SWG\Get(
    *     path="/api/pagos-detalle",
    *     summary="Mostrar los pagos del servicio",
    *     tags={"Pagos"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los pagos del servicio."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function detalle(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "s.no_convenio";
        $columna = $request['sortBy'] == 'mes' ? "m.nombre" : $columna;
        $columna = $request['sortBy'] == 'anio' ? "p.anio_id" : $columna;
        $columna = $request['sortBy'] == 'monto' ? "p.monto" : $columna;
        $columna = $request['sortBy'] == 'tipo_pago' ? "tp.nombre" : $columna;

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $registros = DB::table('pago as p')
                        ->join('servicio as s','p.servicio_id','s.id')
                        ->leftJoin('mes as m','p.mes_id','m.id')
                        ->leftJoin('tipo_pago as tp','p.tipo_pago_id','tp.id')
                        ->select('p.id','s.no_convenio','m.nombre as mes','p.anio_id as anio','p.monto','tp.nombre as tipo_pago')
                        ->where('p.servicio_id',$request->get('pago'))
                        ->where($columna, 'LIKE', '%' . $criterio . '%')
                        ->orderBy($columna, $orden)
                        ->skip($pagina)
                        ->take($filas)
                        ->get();

        $count = DB::table('pago as p')
                    ->join('servicio as s','p.servicio_id','s.id')
                    ->leftJoin('mes as m','p.mes_id','m.id')
                    ->leftJoin('tipo_pago as tp','p.tipo_pago_id','tp.id')
                    ->where('p.servicio_id',$request->get('pago'))
                    ->where($columna, 'LIKE', '%' . $criterio . '%')
                    ->count();
               
        $data = array(
            'total' => $count,
            'data' => $registros,
        );

        return response()->json($data, 200);
    }
    
    private function comprobarPagoMensual($request)
    {
        $verificar = DB::table('pago')
                        ->select('id')
                        ->where('mes_id',$request->mes)
                        ->where('servicio_id',$request->servicio)
                        ->where('anio_id',$request->anio)
                        ->where('tipo_pago_id',$request->tipo_pago)
                        ->first();

        return $verificar ? true : false;
    }

    public function comprobarPagoUnico($request)
    {
        $tipo = TipoPago::where('unico',1)->where('id',$request->tipo_pago)->first();

        if($tipo)
        {
            $verificar = DB::table('pago')
                        ->select('id')
                        ->where('servicio_id',$request->servicio)
                        ->where('tipo_pago_id',$tipo->id)
                        ->first();    

            return $verificar ? true : false;
        }
        else
        {
            return false;
        }
        
    }
}
