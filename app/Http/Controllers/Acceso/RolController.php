<?php

namespace App\Http\Controllers\Acceso;

use App\Rol;
use App\PermisoRol;
use App\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;
    
class RolController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/roles",
    *     summary="Mostrar roles",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los roles."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "nombre";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $usuarios = DB::table('rol') 
                ->select('id','nombre','descripcion') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('rol')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $usuarios,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Post(
    *     path="/api/roles",
    *     summary="Guardar nuevos roles",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Rol",
    *          description="Datos del nuevo rol",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos roles."
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
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'permisos' => 'nullable|array'
            ];

        $this->validate($request, $rules);

        return DB::transaction(function() use($request){

            $rol = new Rol();
            $rol->nombre = $request->get('nombre');
            $rol->descripcion = $request->get('descripcion');
            $rol->save();

            foreach ($request->permisos as $key => $value) {
                PermisoRol::create([
                    'rol_id' => $rol->id,
                    'permiso_id' => $value
                ]);
            }

            return $this->showOne($rol, 201);
        });
    }

    /**
    * @SWG\Get(
    *     path="/api/roles/{id}/edit",
    *     summary="Obtener un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del rol a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit($id)
    {
        $entidad = Rol::with('permiso_rol')
                        ->where('rol.id',$id)
                        ->first();

        return $this->showOne($entidad);
        //return response()->json(['data' => $entidad]);
    }
    /**
    * @SWG\PUT(
    *     path="/api/roles/{rol}",
    *     summary="Actualizar un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Rol",
    *          description="Datos del rol a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, Rol $role)
    {
        $rules = [
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'permisos' => 'nullable|array'
            ];

        $this->validate($request, $rules);

        return DB::transaction(function() use($request, $role){

            $role->permiso_rol()->delete();

            foreach ($request->permisos as $key => $value) {
                PermisoRol::create([
                    'rol_id' => $role->id,
                    'permiso_id' => $value
                ]);
            }

            $role->nombre = $request->get('nombre');
            $role->descripcion = $request->get('descripcion');
            $role->save();

            return $this->showOne($role,201);
        });

    }

    /**
    * @SWG\DELETE(
    *     path="/api/roles/{id}",
    *     summary="Eliminar un rol especifico",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del rol a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un rol especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy(Rol $role)
    {
        $verificar =  UsuarioRol::where('rol_id',$role->id)->first();

        if($verificar)
        {
            return $this->errorResponse("No se puede eliminar de forma permanente porque tiene registros asociados", 423);
        }

        $accion = $role;
        $role->delete();

        return $this->showOne($accion);
    }
     /**
    * @SWG\GET(
    *     path="/api/roles-obtener",
    *     summary="Obtener todos los roles",
    *     tags={"Roles"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Obtiene todos los roles sin paginar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Listado de todos los roles"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */

    public function obtener()
    {
        $roles = Rol::all();

        return $this->showAll($roles);
    }
}
