<?php

use App\Mes;
use App\Anio;
use App\Comite;
use App\Sector;
use App\TipoPago;
use App\Autorizacion;
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
            ['nombre' => 'Pago de instalación','monto' => '500.00','descripcion'=>'Pago por derecho de instalación','unico' => 1],
            ['nombre' => 'Canon de agua','monto' => '15.00','descripcion'=>'Pago por concepto mensual','unico' => 0],
         ]);
        
        Comite::insert([
            ['nombres' =>'Selvin','apellidos' =>'Escobar','puesto' =>'Presidente'],
            ['nombres' =>'Armando','apellidos' =>'Pérez','puesto' =>'Tesorero'],
            ['nombres' =>'Gabriel','apellidos' =>'Martínez','puesto' =>'Secretario'],
        ]);

        Mes::insert([
            ['nombre' => 'Enero'],
            ['nombre' => 'Febrero'],
            ['nombre' => 'Marzo'],
            ['nombre' => 'Abril'],
            ['nombre' => 'Mayo'],
            ['nombre' => 'Junio'],
            ['nombre' => 'Julio'],
            ['nombre' => 'Agosto'],
            ['nombre' => 'Septiembre'],
            ['nombre' => 'Octubre'],
            ['nombre' => 'Noviembre'],
            ['nombre' => 'Diciembre']            
        ]);

        Anio::insert([
            ['id' => '2020','nombre' => '2020'],
            ['id' => '2021','nombre' => '2021'],
            ['id' => '2022','nombre' => '2022'],
            ['id' => '2023','nombre' => '2023'],
            ['id' => '2024','nombre' => '2024'],
            ['id' => '2025','nombre' => '2025'],
            ['id' => '2026','nombre' => '2026'],
        ]);

        Autorizacion::create([
            'nombre_comite' => 'Comité de Agua Potable, Aldea Platanares',
            'municipio_departamento' => 'Guazacapán, Santa Rosa',
            'autorizacion' => '40-2014KNZM',
            'registro_contraloria' => '06-09-09 F170 L04',
            'fecha' => '2017/02/15',
            'activo' => 1
        ]);
    }
}
