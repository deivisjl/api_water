<?php

namespace App\Http\Controllers\Pago;

use App\Pago;
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

        if($this->comprobarPago($request))
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

    private function comprobarPago($request)
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
}
