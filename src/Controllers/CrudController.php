<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCms\Controllers\CrudBaseController;


class CrudController extends CrudBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $model = config('laravel-crudui.models.'.$routeParams['model']);

        $parent_model = null;
        if(isset($routeParams['parent_model']))
        {
            $parent_model = config('laravel-crudui.models.'.$routeParams['parent_model']);
            $parent_field = $model['parent_field'];
            $parent_id = $routeParams['parent_id'];
        }

        parent::__construct($model['model_class'], $parent_model['model_class']);

        $this->page_name = $model['page_name'];
        $this->route_url = $model['route_url'];
        $this->medias_url = isset($model['medias_url']) ? $model['medias_url'] : $this->medias_url;
        $this->global_medias_url = isset($model['global_medias_url']) ? $model['global_medias_url'] : $this->global_medias_url;
        
        $this->route_url = $model['route_url'];

        $this->can_create = isset($model['can_create']) ? $model['can_create'] : $this->can_create;
        $this->can_delete = isset($model['can_delete']) ? $model['can_delete'] : $this->can_delete;
        $this->can_update = isset($model['can_update']) ? $model['can_update'] : $this->can_update;

        $this->list_title = $model['list_title'];
        $this->edit_title = $model['edit_title'];
        $this->add_text = $model['add_text'];
        $this->list_help = isset($model['list_help']) ? $model['list_help'] : "";
        $this->edit_help = isset($model['edit_help']) ? $model['edit_help'] : "";

        $this->sort_by = $model['sort_by'];
        $this->sort_dir = $model['sort_dir'];
        $this->sortable = $model['sortable'];
        $this->limit = $model['limit'];
        $this->where = isset($model['where']) ? $model['where'] : [];
        $this->orWhere = isset($model['orWhere']) ? $model['orWhere'] : [];
        $this->where_relation = isset($model['where_relation']) ? $model['where_relation'] : [];
        $this->defaults = isset($model['defaults']) ? $model['defaults'] : [];

        $this->fields = $model['fields'];

        $this->layout_view = isset($model['layout']) ? $model['layout'] : $this->layout_view;
        $this->list_view = isset($model['list_view']) ? $model['list_view'] : $this->list_view;
        $this->edit_view = isset($model['edit_view']) ? $model['edit_view'] : $this->edit_view;
        
        $this->initFields();

        if($parent_model)
        {
            $this->where[$parent_field] = $parent_id;
            $this->defaults[$parent_field] = $parent_id;

            $route_url = $this->route_url;
            $route_url = substr($route_url, 0, strripos($route_url, '/'));
            $route_url .= '/'.$routeParams['parent_model'].'/'.$parent_id.'/'.$routeParams['model'];
            $this->route_url = $route_url;
            //var_dump($route_url);
            //exit;
        }
    }

    /*public function getEditItem(Request $request, $model = null, $id)
    {
        return parent::getEditItem($request);
    }*/

    /*public function postUpdateItem(Request $request, $model, $id = null)
    {
        return parent::postUpdateItem($request, $id);
    }

    public function getDeleteItem(Request $request, $model, $id = null)
    {
        return parent::getDeleteItem($request, $id);
    }*/
}
