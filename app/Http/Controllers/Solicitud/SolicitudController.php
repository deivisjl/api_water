<?php

namespace App\Http\Controllers\Solicitud;

use App\Rechazo;
use App\Servicio;
use Carbon\Carbon;
use App\EstadoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class SolicitudController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/solicitudes",
    *     summary="Mostrar solicitudes",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todas las solicitudes."
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
        $columna = $request['sortBy'] == 'apellidos' ? 'u.primer_apellido' : $columna;
        $columna = $request['sortBy'] == 'sector' ? 'se.nombre' : $columna;
        $columna = $request['sortBy'] == 'correo_electronico' ? 'u.correo_electronico' : $columna;

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $estado = EstadoServicio::where('inicia_tramite',1)->first();

        $solicitudes = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->select('s.id',DB::raw('CONCAT_WS(" ",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS(" ",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico')
                ->whereNull('s.deleted_at') 
                ->where('s.estado_servicio_id',$estado->id)
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->whereNull('s.deleted_at')
                ->where('s.estado_servicio_id',$estado->id)
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $solicitudes,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Post(
    *     path="/api/solicitudes",
    *     summary="Guardar nuevos solicitudes",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="solicitud",
    *          description="Datos de la nueva solicitud",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos solicitudes."
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
                'direccion' => 'required',
                'fecha' => 'required|date',
                'referencia' => 'nullable|string',
                'sector_id' => 'required|numeric',
                'usuario_id' => 'required|numeric',
            ];

        $this->validate($request, $rules);

        $estado = EstadoServicio::where('inicia_tramite',1)->first();

        $servicio = new Servicio();
        $servicio->usuario_id = $request->get('usuario_id');
        $servicio->sector_id = $request->get('sector_id');
        $servicio->direccion = $request->get('direccion');
        $servicio->referencia_direccion = $request->get('referencia');
        $servicio->fecha_solicitud = $request->get('fecha');
        $servicio->estado_servicio_id = $estado->id;
        $servicio->save();        

        return $this->showOne($servicio);
    }

    /**
    * @SWG\Get(
    *     path="/api/solicitudes/{id}/edit",
    *     summary="Obtener una solicitud especifica",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id de la solicitud a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener una solicitud especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(Servicio $solicitude)
    {
        return $this->showOne($solicitude);
    }

    /**
    * @SWG\PUT(
    *     path="/api/solicitudes/{solicitud}",
    *     summary="Actualizar una solicitud especifica",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Rol",
    *          description="Datos de la solicitud a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar una solicitud especifica."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Servicio $solicitude)
    {
        $rules = [
            'direccion' => 'required',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string',
            'sector_id' => 'required|numeric',
            'usuario_id' => 'required|numeric',
        ];

        $this->validate($request, $rules);

        $solicitude->usuario_id = $request->get('usuario_id');
        $solicitude->sector_id = $request->get('sector_id');
        $solicitude->direccion = $request->get('direccion');
        $solicitude->referencia_direccion = $request->get('referencia');
        $solicitude->fecha_solicitud = $request->get('fecha');
        $solicitude->save();        

        return $this->showOne($solicitude);
    }

    /**
    * @SWG\Post(
    *     path="/api/solicitudes-aprobar",
    *     summary="Aprobar solicitudes",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="solicitud",
    *          description="Datos de la aprobación de solicitud",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Aprobar solicitudes."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function aprobar(Request $request)
    {
        $rules = [
            'id' => 'required|numeric|min:1',
            'persona_id' => 'required|', 
            'fecha_visita' => 'required|date',
        ];

        $this->validate($request, $rules);

        $estado = EstadoServicio::where('inicia_tramite',2)->first();

        $fecha = Carbon::now()->format('Y');

        $solicitud = Servicio::findOrFail($request->id);
        $solicitud->no_convenio = $solicitud->id.$fecha;
        $solicitud->fecha_aprobacion = $request->get('fecha_visita');
        $solicitud->fecha_visita = $request->get('fecha_visita');
        $solicitud->comite_id = $request->get('persona_id');
        $solicitud->estado_servicio_id = $estado->id;
        $solicitud->save();

        return $this->showOne($solicitud);
    }
    /**
    * @SWG\Post(
    *     path="/api/solicitudes-rechazar",
    *     summary="Rechazar solicitudes",
    *     tags={"Solicitudes"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="solicitud",
    *          description="Datos del rechazo de solicitud",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Rechazar solicitudes."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function rechazar(Request $request)
    {
        $rules = [
            'id' => 'required|numeric|min:1',
            'persona_id' => 'required|', 
            'fecha_visita' => 'required|date',
            'motivo' => 'required|string'
        ];

        $this->validate($request, $rules);

        $estado = EstadoServicio::where('inicia_tramite',4)->first();

        return DB::transaction(function () use($request,$estado){

            $solicitud = Servicio::findOrFail($request->id);    
            $solicitud->estado_servicio_id = $estado->id;
            $solicitud->fecha_visita = $request->fecha_visita;
            $solicitud->comite_id = $request->persona_id;
            $solicitud->save();

            $rechazo = new Rechazo();
            $rechazo->solicitud_id = $solicitud->id;
            $rechazo->motivo = $request->motivo;
            $rechazo->save();

            return $this->showOne($solicitud); 
        });
    }

}
