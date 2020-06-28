<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
/**
* @SWG\Info(title="API SISCAP", version="1.0")
*/
class ApiController extends Controller
{
    use ApiResponser;
    
    public function __construct(){

		  $this->middleware('auth:api');    	
    }
}
