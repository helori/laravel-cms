<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiPage;
use Helori\LaravelCms\Models\Page;


class PagesController extends Controller
{
	use ApiPage;

    public function __construct(Request $request)
    {
        $this->modelClass = Page::class;
        $this->where = [];
        $this->read_with = [];
        $this->update_except = ['collections', 'pages', 'open', 'image', 'images'];
        $this->sortable = true;
        $this->sortable_nested = true;
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function page(Request $request)
    {
        return view('laravel-cms::admin.page', $this->data);
    }

    public function edit(Request $request, $id)
    {
        $this->data['id'] = $id;
        return view('laravel-cms::admin.page-edit', $this->data);
    }
}
