<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function personalToken()
    {
        return view('tokens/personal-clients');
    }

    public function tokenClients()
    {
        return view('tokens/token-clients');
    }

    public function authorizedClients()
    {
        return view('tokens/authorized-clients');
    }
}
