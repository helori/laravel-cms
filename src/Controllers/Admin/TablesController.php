<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiTable;
use Helori\LaravelCms\Models\Table;


class TablesController extends Controller
{
	use ApiTable;

    public function __construct()
    {
        $this->modelClass = Table::class;
        $this->read_with = ['fields' => function ($query) {
		    $query->orderBy('position', 'asc');
		}];
        $this->update_except = ['fields'];
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function table(Request $request)
    {
        $this->init();
        return view('laravel-cms::admin.table', $this->data);
    }

    public function fields(Request $request, $id)
    {
        $this->init();
        $this->data['tableId'] = $id;
        return view('laravel-cms::admin.table-fields', $this->data);
    }
}
