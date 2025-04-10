<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Work extends Controller
{
    public function index()
    {
        return view('main.work');
    }
}
