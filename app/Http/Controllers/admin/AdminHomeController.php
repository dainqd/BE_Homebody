<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class AdminHomeController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function setting()
    {
        return view('admin.settings.index');
    }
}
