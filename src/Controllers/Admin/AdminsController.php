<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Models\Admin;
use Helori\LaravelCms\Requests\Admin as AdminRequest;
use Helori\LaravelCms\Traits\ApiCrud;


class AdminsController extends Controller
{
    use ApiCrud;

    public function __construct()
    {
        parent::__construct();

        $this->modelClass = Admin::class;
        $this->read_with = [];
        $this->update_except = [];
    }

    // -------------------------------------------------------------
    //  View
    // -------------------------------------------------------------
    public function admin(Request $request)
    {
        return view('laravel-cms::admin.admin', $this->data);
    }

    // -------------------------------------------------------------
    //  API
    // -------------------------------------------------------------
    public function create(AdminRequest $request)
    {
        $request->modifyInput();
        return $this->apiCreate($request);
    }
    
    public function update(AdminRequest $request, $id)
    {
        $request->modifyInput();
        return $this->apiUpdate($request, $id);
    }
}
