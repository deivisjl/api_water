<?php

namespace App\Http\Controllers\Solicitud;

use App\Servicio;
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
        $columna = $request['sortBy'] == '' ? 'u.primer_nombre':'u.primer_nombre';
        $columna = $request['sortBy'] == 'nombres' ? 'u.primer_nombre' : $columna; 
        $columna = $request['sortBy'] == 'apellidos' ? 'u.primer_apellido': $columna;
        $columna = $request['sortBy'] == 'sector' ? 'se.nombre': $columna;
        $columna = $request['sortBy'] == 'correo_electronico' ? 'u.correo_electronico': $columna;

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $estado = EstadoServicio::where('inicia_tramite',1)->first();

        $solicitudes = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->select('s.id',DB::raw('CONCAT_WS("",u.primer_nombre,"",u.segundo_nombre,"",u.tercer_nombre) as nombres'),DB::raw('CONCAT_WS("",u.primer_apellido,"",u.segundo_apellido) as apellidos'),'se.nombre as sector','u.correo_electronico')
                ->whereNull('s.deleted_at') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('servicio as s') 
                ->join('users as u','s.usuario_id','u.id')
                ->join('sector as se','s.sector_id','se.id')
                ->whereNull('s.deleted_at')
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function edit(Servicio $solicitude)
    {
        return $this->showOne($solicitude);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servicio $solicitude)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servicio $solicitude)
    {
        $registro = $solicitude;

        $solicitude->delete();

        return $this->showOne($registro);
    }
}
