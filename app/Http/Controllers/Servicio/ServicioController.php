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
    *         description="Mostrar todos los servicios."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
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
    *     path="/api/servicios/{id}",
    *     summary="Mostrar la información asociada a un servicio",
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
    *         description="Mostrar la información asociada a un servicio."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function show($id)
    {
        $registro = Servicio::findOrFail($id);

        return $this->showOne($registro, 200);
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
   /**
    * @SWG\PUT(
    *     path="/api/servicios/{estado}",
    *     summary="Actualizar el estado de un servicio especifico",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Estado del servicio a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar el estado de un servicio especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
   public function update(Request $request, $id)
    {
        $rules = [
                'estado' => 'required|numeric',
            ];

        $this->validate($request, $rules);

        $servicio = Servicio::findOrFail($id);
        $servicio->estado_servicio_id = $request->get('estado');
        $servicio->save();

        return $this->showOne($servicio);
    }

   /**
    * @SWG\Get(
    *     path="/api/servicios-usuario/{id}",
    *     summary="Mostrar los servicios de un usuario",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }}, 
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del usuario de los servicios a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),       
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar los servicios de un usuario."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
   public function serviciosUsuario($id)
   {
        $registros = DB::table('servicio as s')
                        ->join('users as u','s.usuario_id','u.id')
                        ->select('s.id','s.no_convenio',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre,"",u.primer_apellido,"",u.segundo_apellido) as nombre'))
                        ->where('s.id',$id)
                        ->whereIn('s.estado_servicio_id',[2,3])
                        ->get();

        return $this->showAll(collect($registros));
   }
   /**
    * @SWG\Get(
    *     path="/api/servicio-titular/{id}",
    *     summary="Mostrar el titular del servicio",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }}, 
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del servicio",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),       
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar el titular del servicio."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
   public function obtenerUsuarioTitular($id)
    {
        $registro = DB::table('pago as p')
                        ->join('servicio as s','p.servicio_id','s.id')
                        ->join('users as u','s.usuario_id','u.id')
                        ->select(DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre,"",u.primer_apellido,"",u.segundo_apellido) as nombre'))
                        ->where('s.id',$id)
                        ->first();

        return response()->json(['data' => $registro]);
    }

    /**
    * @SWG\Get(
    *     path="/api/servicio-titular-detalle/{id}",
    *     summary="Mostrar el titular del servicio con detalle",
    *     tags={"Servicios"},
    *     security={ {"bearer": {} }}, 
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del servicio",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),       
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar el titular del servicio con detalle."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function obtenerUsuarioTitularDetalle($id)
    {
        $registro = DB::table('servicio as s')
                    ->join('users as u','s.usuario_id','u.id')
                    ->select('s.id','s.no_convenio',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre,"",u.primer_apellido,"",u.segundo_apellido) as nombre'))
                    ->where('s.id',$id)
                    ->first();

        return response()->json(['data' => $registro]);
    }
}
