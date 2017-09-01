<?php

namespace Helori\LaravelCms\Controllers;

use Helori\LaravelCms\Controllers\Controller;
use Illuminate\Http\Request;


class FrontController extends Controller
{
    public function home(Request $request)
    {
        return view('laravel-cms::home', []);
    }

    public function medias(Request $request)
    {
        return view('laravel-cms::medias', []);
    }

    public function pages(Request $request)
    {
        return view('laravel-cms::pages', []);
    }

    public function blog(Request $request)
    {
        return view('laravel-cms::blog', []);
    }
}
