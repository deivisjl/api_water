<?php

use App\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 20; $i++) { 
            Book::create([
                'name' => 'libro '.$i,
                'edition' => $i < 10 ? '200'.$i : '20'.$i,
            ]);
        }
    }
}
