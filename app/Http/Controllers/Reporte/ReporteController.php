<?php

namespace App\Http\Controllers\Reporte;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ReporteController extends ApiController
{
    private $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    /**
    * @SWG\Post(
    *     path="/api/grafica-recaudacion-general",
    *     summary="Crear gráfico de reporte de recaudación",
    *     tags={"Reportes gráficos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Fechas",
    *          description="Rango de fechas",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Devuelve objeto json con los datos de recaudacion general."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function graficaRecaudacionGeneral(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            if($desde >= $hasta)
            {
                throw new \Exception("Debe seleccionar un rango válido", 1);
            }

            $diferencia = $hasta->diffInDays($desde);

            if($diferencia > 365)
            {
                throw new \Exception("El rango no debe ser mayor a un año", 1);
                
            }

            $registros = DB::table('pago')
                            ->select(DB::raw('SUM(monto) as monto'),DB::raw('DATE_FORMAT(created_at,"%m-%Y") as mes'))
                            ->whereBetween('created_at', [$desde, $hasta])
                            ->groupBy(DB::raw('DATE_FORMAT(created_at,"%m-%Y")'))
                            ->orderBy(DB::raw('DATE_FORMAT(created_at,"%m-%Y")'),'desc')
                            ->get();

            $resp = $this->construirRespuestaRecaudacion($registros);

            return response()->json(['data' => $resp],200);


        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),423);
        }
    }
    
    public function construirRespuestaRecaudacion($data)
    {
        $respuesta = array();

        $respuesta[0] = ['Mes','Monto'];

        foreach ($data as $key => $item) 
        {
            $fecha = $item->mes;

            $numero = explode("-",$fecha);

            if($numero[0] < 10)
            {
                $numero2 = explode("0",$numero[0]);
            }
            else
            {
                $numero2[1] = $numero[0];
            }


            $respuesta[$key + 1] = [$this->meses[$numero2[1]]." ".$numero[1], (float)$item->monto];
        }

        return $respuesta;
    }
    /**
    * @SWG\Post(
    *     path="/api/grafica-solicitud-servicio",
    *     summary="Crear gráfico de reporte de solicitud de servicio",
    *     tags={"Reportes gráficos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Fechas",
    *          description="Rango de fechas",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Devuelve objeto json con los datos de solicitudes de servicios."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function graficaSolicitudServicio(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));            

            $registros = DB::table('servicio as s')
                            ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                            ->select(DB::raw('COUNT(s.id) as cantidad'),'es.nombre')
                            ->whereBetween('s.created_at', [$desde." 00:00:00", $hasta." 23:59:59"])
                            ->groupBy('es.nombre')
                            ->get();

            $resp = $this->construirSolicitudServicio($registros);

            return response()->json(['data' => $resp], 200);
        } 
        catch (Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),423);
        }

    }
    
    public function construirSolicitudServicio($data)
    {
        $resp = array();

        $resp[0] = ['Tipo','Cantidad'];

        foreach ($data as $key => $item) 
        {
            $resp[$key + 1] = [$item->nombre, (float)$item->cantidad];
        }

        return $resp;
    }
    /**
    * @SWG\Post(
    *     path="/api/grafica-recaudacion-tipo",
    *     summary="Crear gráfico de reporte de recaudación por tipo",
    *     tags={"Reportes gráficos"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Fechas",
    *          description="Rango de fechas",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Devuelve objeto json con los datos de recaudación por tipo."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function graficaRecaudacionTipoServicio(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            $registros = DB::table('pago as p')
                            ->join('tipo_pago as tp','p.tipo_pago_id','tp.id')
                            ->select(DB::raw('SUM(p.monto) as monto'),'tp.id','tp.nombre')
                            ->whereBetween('p.created_at', [$desde, $hasta])
                            ->groupBy('tp.id','tp.nombre')
                            ->get();

            $resp = $this->construirRecaudacionTipoServicio($registros);

            return response()->json(['data' => $resp],200);
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage(),423);
        }
    }

    public function construirRecaudacionTipoServicio($data)
    {
        $resp = array();

        $resp[0] = ['Tipo','Monto'];

        foreach ($data as $key => $item) 
        {
            $resp[$key+1] = [$item->nombre,(float)$item->monto];
        }

        return $resp;
    }
}
