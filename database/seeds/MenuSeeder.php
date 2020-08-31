<?php

use App\Imports\MenuImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$menu = Excel::import(new MenuImport, 'database/seeds/Imports/Menu.xlsx');
    }
}
