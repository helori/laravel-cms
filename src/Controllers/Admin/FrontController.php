<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Helori\LaravelCms\Controllers\Controller;
use Illuminate\Http\Request;


class FrontController extends Controller
{
    public function home(Request $request)
    {
    	$this->init();
        return view('laravel-cms::admin.home', $this->data);
    }
}
