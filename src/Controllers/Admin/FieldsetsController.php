<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiFieldset;
use Helori\LaravelCms\Models\Fieldset;


class FieldsetsController extends Controller
{
	use ApiFieldset;

    public function __construct()
    {
        $this->modelClass = Fieldset::class;
        $this->read_with = ['fields' => function ($query) {
		    $query->orderBy('position', 'asc');
		}];
        $this->update_except = ['fields'];
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function fieldset(Request $request)
    {
        return view('laravel-cms::admin.fieldset', $this->data);
    }
}
