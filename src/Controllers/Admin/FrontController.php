<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Helori\LaravelCms\Controllers\Controller;
use Illuminate\Http\Request;


class FrontController extends Controller
{
    protected $data = [];

    public function home(Request $request)
    {
        return view('laravel-cms::admin.home', $this->data);
    }
}
