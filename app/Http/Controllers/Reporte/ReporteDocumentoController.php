<?php

namespace App\Http\Controllers\Reporte;

use App\Pago;
use App\TipoPago;
use Carbon\Carbon;
use App\EstadoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ReporteDocumentoController extends ApiController
{

    private $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    /**
    * @SWG\Get(
    *     path="/api/reporte-obtener-estados",
    *     summary="Mostrar un listado de estados",
    *     tags={"Reportes pdf"},
    *     security={ {"bearer": {} }},
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar un listado de estados."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function obtenerEstadoServicio()
    {
    	$estados = EstadoServicio::all();
        
        return $this->showAll($estados);
    }
    /**
    * @SWG\Post(
    *     path="/api/generar-reporte-servicios",
    *     summary="Crear pdf de reporte de servicios",
    *     tags={"Reportes pdf"},
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
    *         description="Crear pdf de reporte de servicios."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function generarReporteServicio(Request $request)
    {
        $rules = [
            'estado'=>'required'
        ];

        $this->validate($request, $rules);

        try 
        {

            $registros = DB::table('servicio as s') 
                        ->join('users as u','s.usuario_id','u.id')
                        ->join('sector as se','s.sector_id','se.id')
                        ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                        ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico','es.nombre as estado','u.email as dpi','s.no_convenio')
                        ->where('s.estado_servicio_id',$request->get('estado'))
                        ->get();

            $estado = EstadoServicio::findOrFail($request->get('estado'));

            $pdf = \PDF::loadView('pdf-servicio',['registros' => $registros, 'estado' => $estado->nombre])->setPaper('letter','landscape');
            
            $fecha = Carbon::now()->format('dmY_h:m:s');

            return $pdf->stream('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }
     /**
    * @SWG\Post(
    *     path="/api/generar-reporte-solicitudes",
    *     summary="Crear pdf de reporte de solicitudes",
    *     tags={"Reportes pdf"},
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
    *         description="Crear pdf de reporte de solicitudes en un rango determinado."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function generarReporteSolicitudes(Request $request)
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

            $registros = DB::table('servicio as s') 
                        ->join('users as u','s.usuario_id','u.id')
                        ->join('sector as se','s.sector_id','se.id')
                        ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                        ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico','es.nombre as estado','u.email as dpi','s.no_convenio','s.fecha_solicitud')
                        ->whereBetween('s.fecha_solicitud',[$desde, $hasta])
                        ->get();            

            $pdf = \PDF::loadView('pdf-solicitudes',['registros' => $registros, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','landscape');
            
            $fecha = Carbon::now()->format('dmY_h:m:s');

            return $pdf->stream('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }
    /**
    * @SWG\Post(
    *     path="/api/generar-reporte-morosos",
    *     summary="Crear pdf de reporte de morosos",
    *     tags={"Reportes pdf"},
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
    *         description="Crear pdf de reporte de morosos en un rango determinado."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function generarReporteMorosos(Request $request)
    {
        $rules = [
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

        try 
        {
            $mes = Carbon::parse($request->get('hasta'))->format('m');
            $year = Carbon::parse($request->get('hasta'))->format('Y');

            $solventes = Pago::where('mes_id',$mes)
                                ->where('anio_id',$year)
                                ->where('tipo_pago_id',TipoPago::CANON_DE_AGUA)
                                ->get('servicio_id')->toArray();
            
            $registros = DB::table('servicio as s') 
                        ->join('users as u','s.usuario_id','u.id')
                        ->join('sector as se','s.sector_id','se.id')
                        ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                        ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),
                            DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector',
                            'u.email as dpi','s.no_convenio','es.nombre as estado')
                        ->whereNotIN('s.id',$solventes)
                        ->whereIN('s.estado_servicio_id',[EstadoServicio::VIGENTE, EstadoServicio::SUSPENDIDO])
                        ->orderBy('u.primer_nombre','asc')
                        ->get();

            $hasta = Carbon::parse($request->get('hasta'))->format('d/m/Y');

            $pdf = \PDF::loadView('pdf-morosos',['registros' => $registros, 'hasta' => $hasta])->setPaper('letter','landscape');
            
            $fecha = Carbon::now()->format('dmY_h:m:s');

            return $pdf->stream('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }
    /**
    * @SWG\Post(
    *     path="/api/generar-reporte-recaudacion",
    *     summary="Crear pdf de reporte recaudación",
    *     tags={"Reportes pdf"},
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
    *         description="Crear pdf de reporte recaudación en un rango determinado."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function generarReporteRecaudacion(Request $request)
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

            $pdf = \PDF::loadView('pdf-recaudacion',['registros' => $resp, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','portrait');
            
            $fecha = Carbon::now()->format('dmY_h:m:s');

            return $pdf->stream('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }
    public function construirRespuestaRecaudacion($data)
    {
        $respuesta = array();

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

            $respuesta[$key] = array('mes' => $this->meses[$numero2[1]], 'year' => $numero[1], 'monto' => (float)$item->monto);
        }

        return $respuesta;
    }
}
