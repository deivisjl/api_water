<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BookController extends ApiController
{
    public function getBooks()
    {
        $books = Book::all();

        return $this->showAll($books, 200);
    }
}
