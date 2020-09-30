<?php

namespace App\Http\Controllers\MiServicio;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class MiServicioController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/mis-servicios",
    *     summary="Mostrar los servicios asociados al usuario en sesion",
    *     tags={"Mis servicios"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los servicios del usuario"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */

    public function index(Request $request)
    {
        $usuario = $request->user();

        $columna = $request['sortBy'] ? $request['sortBy'] : "s.no_convenio";
        $columna = $request['sortBy'] == 'dpi' ? "u.email" : $columna;
        $columna = $request['sortBy'] == 'nombre' ? "u.primer_nombre" : $columna;
        $columna = $request['sortBy'] == 'apellidos' ? "u.primer_apellido" : $columna;
        $columna = $request['sortBy'] == 'sector' ? "se.nombre" : $columna;
        $columna = $request['sortBy'] == 'correo_electronico' ? "u.correo_electronico" : $columna;
        $columna = $request['sortBy'] == 'estado' ? "es.nombre" : $columna;

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $servicios = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico','es.nombre as estado','u.email as dpi','s.no_convenio')
                ->whereNull('s.deleted_at') 
                ->whereIn('s.estado_servicio_id',[2,3])
                ->where('s.usuario_id', $usuario->id)
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->join('estado_servicio as es','s.estado_servicio_id','es.id')
                ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico','es.nombre as estado')
                ->whereNull('s.deleted_at') 
                ->where('s.usuario_id', $usuario->id)
                ->whereIn('s.estado_servicio_id',[2,3])
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $servicios,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Get(
    *     path="/api/mis-servicios-detalle/{request}",
    *     summary="Listar los pagos del servicio",
    *     tags={"Mis servicios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="request",
    *          description="Petición con las opciones para mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener el detalle de los pagos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */

    public function detalle(Request $request)
    {
        $usuario = $request->user();

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
                        ->where('p.servicio_id',$request->get('servicio'))
                        ->where('s.usuario_id', $usuario->id)
                        ->where($columna, 'LIKE', '%' . $criterio . '%')
                        ->orderBy($columna, $orden)
                        ->skip($pagina)
                        ->take($filas)
                        ->get();

        $count = DB::table('pago as p')
                    ->join('servicio as s','p.servicio_id','s.id')
                    ->leftJoin('mes as m','p.mes_id','m.id')
                    ->leftJoin('tipo_pago as tp','p.tipo_pago_id','tp.id')
                    ->where('p.servicio_id',$request->get('servicio'))
                    ->where('s.usuario_id', $usuario->id)
                    ->where($columna, 'LIKE', '%' . $criterio . '%')
                    ->count();
               
        $data = array(
            'total' => $count,
            'data' => $registros,
        );

        return response()->json($data, 200);
    }

   
}
