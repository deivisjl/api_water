<?php

namespace App\Http\Controllers\Catalogos;

use App\Autorizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AutorizacionController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/autorizaciones",
    *     summary="Mostrar registros de las autorizaciones",
    *     tags={"Autorizaciones"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos las autorizaciones de comité"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "nombre_comite";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $comites = DB::table('autorizacion') 
                ->select('id','nombre_comite','municipio_departamento','autorizacion','registro_contraloria','fecha','activo') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('autorizacion')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $comites,
        );

        return response()->json($data, 200);
    }


    /**
    * @SWG\Post(
    *     path="/api/autorizaciones",
    *     summary="Guardar nuevo registro de autorización",
    *     tags={"Autorizaciones"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos de la nueva autorización",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nueva autorización."
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
            'nombre_comite' => 'required',
            'municipio' => 'required',
            'no_autorizacion' => 'required',
            'no_contraloria' => 'required',
            'fecha' => 'required|date',
        ];
        
        $this->validate($request, $rules);

        $registro = new Autorizacion();
        $registro->nombre_comite = $request->get('nombre_comite');
        $registro->municipio_departamento = $request->get('municipio');
        $registro->autorizacion = $request->get('no_autorizacion');
        $registro->registro_contraloria = $request->get('no_contraloria');
        $registro->fecha = $request->get('fecha');
        $registro->save();

        return $this->showOne($registro);
    }


    /**
    * @SWG\Get(
    *     path="/api/autorizaciones/{id}/edit",
    *     summary="Obtener una autorización especifica",
    *     tags={"Autorizaciones"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id de la autorización a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un autorización especifica."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(Autorizacion $autorizacione)
    {
        return $this->showOne($autorizacione);
    }

    /**
    * @SWG\PUT(
    *     path="/api/autorizaciones/{autorizacion}",
    *     summary="Actualizar una autorización especifica",
    *     tags={"Autorizaciones"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos de la autorización a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un autorización especifica."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Autorizacion $autorizacione)
    {
        $rules = [
            'nombre_comite' => 'required',
            'municipio' => 'required',
            'no_autorizacion' => 'required',
            'no_contraloria' => 'required',
            'fecha' => 'required|date',
        ];
        
        $this->validate($request, $rules);

        $autorizacione->nombre_comite = $request->get('nombre_comite');
        $autorizacione->municipio_departamento = $request->get('municipio');
        $autorizacione->autorizacion = $request->get('no_autorizacion');
        $autorizacione->registro_contraloria = $request->get('no_contraloria');
        $autorizacione->fecha = $request->get('fecha');
        $autorizacione->save();

        return $this->showOne($autorizacione);
    }

    /**
    * @SWG\GET(
    *     path="/api/autorizaciones-habiltar/{id}",
    *     summary="Habilitar una autorización especifica",
    *     tags={"Autorizaciones"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id de la autorización a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Registro de autorización habilitada."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function habilitar($id)
    {
        return DB::transaction(function () use($id) {

                $registro = Autorizacion::where('activo',1)->first();
                $registro->activo = 0;
                $registro->save();

                $habilitar = Autorizacion::findOrFail($id);
                $habilitar->activo = 1;
                $habilitar->save();

                return $this->showOne($habilitar);
            });
    }
}
