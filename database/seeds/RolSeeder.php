<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Administra los accesos de la plataforma'
        ]);

        Rol::create([
            'nombre' => 'Digitador',
            'descripcion' => 'Se encarga de registrar la información'
        ]);

        Rol::create([
            'nombre' => 'Regular',
            'descripcion' => 'Puede consultar sus servicios'
        ]);
    }
}
