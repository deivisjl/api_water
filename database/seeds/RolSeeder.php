<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Acceso\Rol;

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
    }
}
