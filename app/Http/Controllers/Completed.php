<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Completed extends Controller
{
    public function index()
    {
        return view('main.completed');
    }
}
