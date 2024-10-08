<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function notFound()
    {
        return view('error.404');
    }

    public function unauthorized()
    {
        return view('error.401');
    }

    public function forbidden()
    {
        return view('error.403');
    }
}
