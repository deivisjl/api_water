<?php

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
            ['nombre' => 'En trámite', 'descripcion' => 'Servicio solicitado'],
            ['nombre' => 'Vigente','descripcion' => 'Servicio vigente'],
            ['nombre' => 'Suspendido', 'descripcion' => 'Servicio suspendido'],
            ['nombre' => 'Revocado', 'descripcion' => 'Servicio revocado']
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
    }
}
