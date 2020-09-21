<?php

namespace App\Http\Controllers\Catalogos;

use App\Mes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class MesController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/meses",
    *     summary="Mostrar todos los registros de meses",
    *     tags={"Meses"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos los registros de meses"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index()
    {
        $registros = Mes::all();

        return $this->showAll($registros);
    }

}
