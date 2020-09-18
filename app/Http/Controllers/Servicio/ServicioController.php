<?php

namespace App\Http\Controllers\Servicio;

use App\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ServicioController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/servicios",
    *     summary="Mostrar servicios",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todas los servicios."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "u.primer_nombre";
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
                ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico','es.nombre as estado')
                ->whereNull('s.deleted_at') 
                ->whereIn('s.estado_servicio_id',[2,3])
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
    *     path="/api/servicios-detalle/{id}",
    *     summary="Mostrar un servicio especifico",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }}, 
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del servicio a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),       
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar un servicio especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
   public function detalle($id)
   {
       $registro = DB::table('servicio as s')
                    ->join('sector as se','s.sector_id','se.id')
                    ->join('users as u','s.usuario_id','u.id')
                    ->select(DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre,"",u.primer_apellido,"",u.segundo_apellido) as nombre'),'se.nombre as sector','s.direccion','s.referencia_direccion','u.correo_electronico','u.email as dpi')
                    ->where('s.id',$id)
                    ->whereNull('u.deleted_at')
                    ->first();
        
        return response()->json(['data' => $registro]);
   }
}
