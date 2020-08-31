<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'primer_nombre' => 'Deivis',
            'primer_apellido' => 'López',
            'email' => '1234567890101',
            'correo_electronico' => 'deivisjl@gmail.com',
            'direccion_residencia' => 'Ciudad',
            'password' => bcrypt('12345')
        ]);
    }
}
