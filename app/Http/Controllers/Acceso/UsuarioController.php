<?php

namespace App\Http\Controllers\Acceso;

use App\User;
use App\Telefono;
use App\UsuarioRol;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class UsuarioController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/usuarios",
    *     summary="Listar usuarios",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "primer_nombre";

        $columna = $columna == 'nombres' ? 'primer_nombre' : $columna;

        $columna = $columna == 'apellidos' ? 'primer_apellido' : $columna;

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $usuarios = DB::table('users') 
                ->select('id',DB::raw('CONCAT_WS("",primer_nombre," ",segundo_nombre," ",tercer_nombre) as nombres'),DB::raw('CONCAT_WS("",primer_apellido," ",segundo_apellido) as apellidos'),'email', 'correo_electronico') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->whereNull('deleted_at')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('users')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->whereNull('deleted_at')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $usuarios,
        );

        return response()->json($data, 200);
    }

    /**
    * @SWG\Post(
    *     path="/api/usuarios",
    *     summary="Guardar nuevos usuarios",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Usuarios",
    *          description="Datos del nuevo usuario",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Guardar nuevos usuarios."
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
            'correo_electronico' => 'nullable|email|unique:users',
            'direccion' => 'required',
            'email' => 'required|numeric|unique:users',
            'primer_apellido' => 'required',
            'primer_nombre' => 'required',
            'segundo_apellido' => 'nullable',
            'segundo_nombre' => 'nullable',
            'telefono' => 'required|numeric',
            'tercer_nombre' => 'nullable'
        ];

        $this->validate($request, $rules, $this->validacion());

        return DB::transaction(function() use($request){

            $usuario = new User();
            $usuario->correo_electronico = $request->get('correo_electronico');
            $usuario->direccion_residencia = $request->get('direccion');
            $usuario->email = $request->get('email');
            $usuario->primer_apellido = $request->get('primer_apellido');
            $usuario->primer_nombre = $request->get('primer_nombre');
            $usuario->segundo_apellido = $request->get('segundo_apellido');
            $usuario->segundo_nombre = $request->get('segundo_nombre');
            $usuario->tercer_nombre = $request->get('tercer_nombre');
            $usuario->password = bcrypt($this->crearCredencial());
            $usuario->save();

            $telefono = new Telefono();
            $telefono->usuario_id = $usuario->id;
            $telefono->numero =  $request->get('telefono');
            $telefono->save();

            return $this->showMessage('Registro generado con éxito', 200);
        });

    }

    /**
    * @SWG\Get(
    *     path="/api/usuarios/{id}/edit",
    *     summary="Obtener un usuario especifico",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del usuario a mostrar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),      
    *     @SWG\Response(
    *         response=200,
    *         description="Obtener un usuario especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function edit($id)
    {
        //
    }

    /**
    * @SWG\PUT(
    *     path="/api/usuarios/{usuario}",
    *     summary="Actualizar un usuario especifico",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="usuario",
    *          description="Datos del usuario a actualizar",
    *          required=true,
    *          in="path",
    *          type="string"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar un usuario especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * @SWG\DELETE(
    *     path="/api/usuarios/{id}",
    *     summary="Eliminar un usuario especifico",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},
    *      @SWG\Parameter(
    *          name="Id",
    *          description="Id del usuario a eliminar",
    *          required=true,
    *          in="path",
    *          type="integer"    
    *      ),
    *     @SWG\Response(
    *         response=200,
    *         description="Eliminar un usuario especifico."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        $registro = $usuario;

        $usuario->delete();

        return $this->showOne($registro);
    }
    /**
    * @SWG\Get(
    *     path="/api/usuarios-roles/{id}",
    *     summary="Listar roles actuales de un usuario",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Listar roles actuales de un usuario."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function roles($id)
    {
        $roles = UsuarioRol::where('usuario_id',$id)
                                ->get();

        return $this->showAll($roles);
    }

    /**
    * @SWG\Post(
    *     path="/api/usuarios-roles/{id}",
    *     summary="Actualizar roles actuales de un usuario",
    *     tags={"Usuarios"},
    *     security={ {"bearer": {} }},        
    *     @SWG\Response(
    *         response=200,
    *         description="Actualizar roles actuales de un usuario."
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function updateRoles(Request $request)
    {
        $rules = [
                'id' => 'required|integer',
                'roles' => 'nullable|array'
            ];

        $this->validate($request, $rules);

        return DB::transaction(function() use($request){

            $usuario = User::findOrFail($request->id);

            $usuario->usuario_rol()->delete();

            foreach ($request->roles as $key => $value) {
                    UsuarioRol::create([
                        'rol_id' => $value,
                        'usuario_id' => $usuario->id
                    ]);
                }
            return $this->showOne($usuario);
        });
        
    }

    public function crearCredencial()
    {
        $credencial = Str::random(8);

        return $credencial;
    }

    public function validacion()
    {
        $messages = [
                        'email.required' => 'El campo dpi es requerido',//'required|numeric|unique:users',
                        'email.numeric' => 'El campo dpi es númerico',
                        'email.unique' => 'El número de dpi ya ha sido utilizado',
                    ];

        return $messages;
    }
}
