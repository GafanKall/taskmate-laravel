<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Health extends Controller
{
    public function index()
    {
        return view('main.health');
    }
}
