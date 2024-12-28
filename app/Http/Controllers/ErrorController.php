<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function notFound()
    {
        return view('error.404');
    }

    public function unauthorized(Request $request)
    {
        $callback = $request->input('callback');
        return view('error.401', compact('callback'));
    }

    public function forbidden(Request $request)
    {
        $callback = $request->input('callback');
        return view('error.403', compact('callback'));
    }
}
