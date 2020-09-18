<?php

namespace App\Http\Controllers\Catalogos;

use App\Comite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ComiteController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/comite",
    *     summary="Mostrar registros del comité",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los integrantes del comité"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "nombres";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $comites = DB::table('comite') 
                ->select('id','nombres','apellidos','puesto') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->whereNull('deleted_at')
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('comite')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->whereNull('deleted_at')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $comites,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Post(
    *     path="/api/comite",
    *     summary="Guardar integrante de comite",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del nuevo integrante",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevo integrante."
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
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'puesto' => 'required|string'
        ];

        $this->validate($request, $rules);

        $comite = new Comite();
        $comite->nombres = $request->nombres;
        $comite->apellidos = $request->apellidos;
        $comite->puesto = $request->puesto;
        $comite->save();

        return $this->showOne($comite);
    }

    /**
    * @SWG\Get(
    *     path="/api/comite/{id}/edit",
    *     summary="Obtener un integrante especifico",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del integrante a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un integrante especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit(Comite $comite)
    {
        return $this->showOne($comite);
    }

    /**
    * @SWG\PUT(
    *     path="/api/comite/{comite}",
    *     summary="Actualizar un integrante especifico",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Registro",
    *          description="Datos del integrante a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un integrante especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Comite $comite)
    {
        $rules = [
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'puesto' => 'required|string'
        ];

        $this->validate($request, $rules);

        $comite->nombres = $request->nombres;
        $comite->apellidos = $request->apellidos;
        $comite->puesto = $request->puesto;
        $comite->save();

        return $this->showOne($comite);
    }

    /**
    * @SWG\DELETE(
    *     path="/api/comite/{id}",
    *     summary="Eliminar un integrante especifico",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del integrante a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un integrante especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(Comite $comite)
    {
        $accion = $comite;

        $comite->delete();

        return $this->showOne($accion,201);
    }
    /**
    * @SWG\Get(
    *     path="/api/comite-obtener",
    *     summary="Obtener todos los integrantes activos",
    *     tags={"Comité"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener todos los integrantes activos."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function obtener()
    {
        $integrantes = DB::table('comite')
                        ->select('id',DB::raw('CONCAT_WS(" ",nombres,"",apellidos) as nombre'))
                        ->whereNull('deleted_at')
                        ->get();
        
        return $this->showAll(collect($integrantes));
    }
}
