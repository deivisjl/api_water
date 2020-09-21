<?php

namespace App\Http\Controllers\Catalogos;

use App\Anio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AnioController extends ApiController
{
    /**
    * @SWG\Get(
    *     path="/api/anios",
    *     summary="Mostrar los registros de años",
    *     tags={"Años"},
    *     security={ {"bearer": {} }},    
    *     @SWG\Response(
    *         response=200,
    *         description="Mostrar todos años"
    *     ),
    *     @SWG\Response(
    *         response="default",
    *         description="Falla inesperada. Intente luego"
    *     )
    * )
    */
    public function index()
    {
        $registros = Anio::all();

        return $this->showAll($registros);
    }
}
