<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCms\Controllers\CrudSingleBaseController;


class CrudSingleController extends CrudSingleBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $model = config('laravel-crudui.models.'.$routeParams['model']);

        parent::__construct($model['model_class']);

        $this->item_id = isset($model['item_id']) ? $model['item_id'] : 1;
        $this->page_name = $model['page_name'];
        $this->route_url = $model['route_url'];
        $this->medias_url = isset($model['medias_url']) ? $model['medias_url'] : $this->medias_url;
        $this->global_medias_url = isset($model['global_medias_url']) ? $model['global_medias_url'] : $this->global_medias_url;
        $this->edit_title = isset($model['edit_title']) ? $model['edit_title'] : "";
        $this->edit_help = isset($model['edit_help']) ? $model['edit_help'] : "";
        $this->fields = $model['fields'];
        $this->layout_view = isset($model['layout']) ? $model['layout'] : $this->layout_view;
    }
}
