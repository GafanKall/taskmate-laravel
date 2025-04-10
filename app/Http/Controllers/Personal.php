<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Personal extends Controller
{
    public function index()
    {
        return view('main.personal');
    }
}
