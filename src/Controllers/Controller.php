<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Auth;
use Helori\LaravelCms\Models\Table;
use Helori\LaravelCms\Models\Collection;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $data = [];

    public function init(){
    	if(Auth::guard('admin')->check()){
            $this->data['tables'] = Table::where('in_admin', true)->get();
            $this->data['collections'] = Collection::all();
        }
    }
}
