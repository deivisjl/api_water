<?php

namespace App\Http\Controllers\Acceso;

use App\User;
use App\UsuarioRol;
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
        $columna = $request['sortBy'] ? $request['sortBy'] : "name";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $usuarios = DB::table('users') 
                ->select('id','name','email') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('users')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $usuarios,
        );

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
    * @SWG\Get(
    *     path="/api/'usuarios-roles/{id}",
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
    *     path="/api/'usuarios-roles/{id}",
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
}
