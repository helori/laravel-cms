<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Collection;


class CollectionsController extends Controller
{
	use ApiCrud;

    public function __construct()
    {
        $this->modelClass = Collection::class;
        $this->read_with = ['posts' => function ($query) {
		    $query->orderBy('position', 'asc');
		}];
        $this->update_except = ['posts'];
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function collection(Request $request)
    {
        return view('laravel-cms::admin.collection', $this->data);
    }
}
