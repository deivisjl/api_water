<?php

namespace App\Http\Controllers\Backup;

use App\Backup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class BackupController extends ApiController
{
    
    public function crearRespaldo()
    {
        try 
        {
            $query = 'SHOW TABLES';

            $registros = DB::select($query);

            $script = "";

            $backup_file_name = "";

            foreach ($registros as $key => $tabla)  {

                $querySelect = "SELECT * FROM ".$tabla->Tables_in_agua;
                
                $resp = DB::select($querySelect);

                foreach ($resp as $campo => $valor) {
                    
                    $registro = collect($valor);

                    $cantidadColumnas = sizeof($registro);

                    $script .= "INSERT INTO ".$tabla->Tables_in_agua." VALUES (";

                    for ($i=0; $i < $cantidadColumnas; $i++) 
                    { 
                        $valores = $registro->values();
                        
                        if(isset($valores[$i]))
                        {
                            $script.='"'.$valores[$i].'"';
                        }
                        else
                        {
                            $script.='""';
                        }
        
                        if($i < ($cantidadColumnas - 1))
                        {
                            $script .=',';
                        }
                    }
        
                    $script .= ");\n";
                }
                $script.="\n";
            }
            $script .= "\n"; 
            if(!empty($script))
            {
                $fecha = Carbon::now()->format('d_m_Y_h_m_s');
                
                $backup_file_name = 'SISCAP' . '_backup_' . $fecha . '.sql';
                $fileHandler = fopen(storage_path() .'/app//'. $backup_file_name, 'w+');
                $number_of_lines = fwrite($fileHandler, $script);
                fclose($fileHandler);
                
                $nuevoRegistro = new Backup();
                $nuevoRegistro->nombre = $backup_file_name;
                $nuevoRegistro->save();
            }

            return $this->showOne($nuevoRegistro);
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }

    public function mostrar(Request $request)
    {
        $columna = $request['sortBy'] ? $request['sortBy'] : "nombre";

        $criterio = $request['search'];

        $orden = $request['sortDesc'] ? 'desc' : 'asc';

        $filas = $request['perPage'];

        $pagina = $request['page'];

        $respaldos = DB::table('backup') 
                ->select('id','nombre','created_at') 
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->orderBy($columna, $orden)
                ->skip($pagina)
                ->take($filas)
                ->get();
              
        $count = DB::table('backup')
                ->where($columna, 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'total' => $count,
            'data' => $respaldos,
        );

        return response()->json($data, 200);
    }

    public function descargar($id)
    {
        $archivo = Backup::findOrFail($id);
        
        return response()->download(storage_path("app/{$archivo->nombre}"));
    }

    public function eliminar($id)
    {
        try 
        {
            $registro = Backup::findOrFail($id);

            Storage::delete($registro->nombre);

            $objeto = $registro;

            $registro->delete();

            return $this->showOne($registro);
        } 
        catch (\Exception $ex) 
        {
            return $this->errorResponse($ex->getMessage(),423);
        }
    }
}
