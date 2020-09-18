<?php

use App\Comite;
use App\Sector;
use App\TipoPago;
use App\EstadoServicio;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoServicio::insert([
            ['nombre' => 'En trámite', 'descripcion' => 'Servicio solicitado','inicia_tramite' => '1'],
            ['nombre' => 'Vigente','descripcion' => 'Servicio vigente','inicia_tramite' => '2'],
            ['nombre' => 'Suspendido', 'descripcion' => 'Servicio suspendido','inicia_tramite' => '3'],
            ['nombre' => 'Rechazado', 'descripcion' => 'Servicio rechazado','inicia_tramite' => '4']
        ]);

        Sector::insert([
            ['nombre' => 'Sector El Centro'],
            ['nombre' => 'Sector El Camalote'],
            ['nombre' => 'Sector El Cuchubal'],
            ['nombre' => 'Sector Barranca Honda'],
            ['nombre' => 'Sector Bethania']
        ]);

        TipoPago::insert([
            'nombre' => 'Canon de agua'
        ]);
        
        Comite::insert([
            ['nombres' =>'Selvin','apellidos' =>'Escobar','puesto' =>'Presidente'],
            ['nombres' =>'Armando','apellidos' =>'Pérez','puesto' =>'Tesorero'],
            ['nombres' =>'Gabriel','apellidos' =>'Martínez','puesto' =>'Secretario'],
        ]);

    }
}
