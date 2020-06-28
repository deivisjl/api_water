<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
/**
* @SWG\Info(title="API SISCAP", version="1.0")
*/
class ApiController extends Controller
{
    use ApiResponser;
    
    public function __construct(){

		  $this->middleware('auth:api');    	
    }

    protected function allowedAdminAction()
    {
        if(!Gate::allows('admin-action')){
            throw new AuthorizationException('Esta acción no está permitida');
        }
    }
}
