<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Models\Fieldset;


class FrontController extends Controller
{
    protected $data = [];

    public function __construct(Request $request)
    {
        $this->data['fieldsets'] = Fieldset::all();
    }

    public function home(Request $request)
    {
        return view('laravel-cms::home', $this->data);
    }

    public function medias(Request $request)
    {
        return view('laravel-cms::medias', $this->data);
    }

    public function pages(Request $request)
    {
        return view('laravel-cms::pages', $this->data);
    }

    public function blog(Request $request)
    {
        return view('laravel-cms::blog', $this->data);
    }

    public function fieldsets(Request $request)
    {
        return view('laravel-cms::fieldsets', $this->data);
    }

    public function elements(Request $request, $slug)
    {
        $fieldset = Fieldset::where('slug', $slug)->firstOrFail();
        $this->data['fieldset_id'] = $fieldset->id;
        return view('laravel-cms::elements', $this->data);
    }
}
