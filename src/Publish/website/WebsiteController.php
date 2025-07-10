<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WebsiteController extends Controller
{
    public function home(Request $request)
    {
        return view('home');
    }
}
