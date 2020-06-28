<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class BookController extends ApiController
{
    public function getBooks()
    {
        $this->allowedAdminAction();

        $books = Book::all();

        return $this->showAll($books, 200);
    }
}
