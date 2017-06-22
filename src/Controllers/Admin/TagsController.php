<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Tag;


class TagsController extends Controller
{
	use ApiCrud;

    public function __construct(Request $request)
    {
        $this->modelClass = Tag::class;
        $this->where = [];
        $this->read_with = [];
        $this->update_except = [];
        $this->sortable = false;
        $this->sortable_nested = false;
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function tag(Request $request)
    {
        $this->init();
        return view('laravel-cms::admin.tag', $this->data);
    }
}
