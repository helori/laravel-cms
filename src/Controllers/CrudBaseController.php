<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCms\CrudUtilities;


class CrudBaseController extends Controller
{
    protected $data = [];
    protected $class_name;

    public function __construct($class_name, $sub_class_name = null)
    {
        $this->class_name = $class_name;
        $this->sub_class_name = $sub_class_name;

        $this->page_name = "model";
        $this->route_url = "/model";
        $this->medias_url = "/medias";
        $this->global_medias_url = config('laravel-crudui.global_medias.route_url');

        $this->can_create = true;
        $this->can_delete = true;
        $this->can_update = true;

        $this->list_title = "Titre de la liste";
        $this->edit_title = "Éditer l'élément";
        $this->add_text = "Ajouter un élément";
        $this->list_help = "";
        $this->edit_help = "";

        $this->sort_by = "name";
        $this->sort_dir = "asc";
        $this->sortable = false;
        $this->limit = 5;
        $this->where = [];
        $this->orWhere = [];
        $this->where_relation = [];
        $this->defaults = [];

        $this->fields = [];

        $this->layout_view = 'laravel-crudui::layout';
        $this->list_view = 'laravel-crudui::list';
        $this->edit_view = 'laravel-crudui::edit';
    }

    protected function initFields()
    {
        foreach($this->fields as &$field)
        {
            if($field['type'] == 'select' && isset($field['options_model']))
            {
                $model = $field['options_model']['model'];

                $orderBy = 'id';
                if(isset($field['options_model']['orderBy'])){
                    $orderBy = $field['options_model']['orderBy'];
                }
                else if(is_array($field['options_model']['field'])){
                    $orderBy = $field['options_model']['field'][0];
                }
                else{
                    $orderBy = $field['options_model']['field'];
                }
                $items = call_user_func(array($model, 'orderBy'), $orderBy);
                if(isset($field['options_model']['where'])) {
                    $items->where($field['options_model']['where']);
                }
                $items = $items->get();

                $options = [];
                foreach($items as $item){
                    $options[$item->id] = [];
                    if(is_array($field['options_model']['field'])){
                        foreach($field['options_model']['field'] as $field_name){
                            $options[$item->id][] = $item->{$field_name};
                        }
                    }
                    else{
                        $options[$item->id][] = $item->{$field['options_model']['field']};
                    }
                    $options[$item->id] = implode(' ', $options[$item->id]);
                }

                //$options = array_pluck($items, $field['options_model']['field'], 'id');
                $field['options'] = $options;
            }
            else if($field['type'] == 'multi-check' && isset($field['options_model']))
            {
                $model = $field['options_model']['model'];
                $items = call_user_func(array($model, 'orderBy'), $field['options_model']['field'])->get();
                $options = array_pluck($items, $field['options_model']['field'], 'id');
                $field['options'] = $options;
            }
        }

        $this->list_fields = array_where($this->fields, function ($field, $key){
            return isset($field['list']) && $field['list'];
        });

        $this->edit_fields = array_where($this->fields, function ($field, $key){
            return (isset($field['edit']) && $field['edit']) || $field['type'] == 'separator';
        });

        $this->create_fields = array_where($this->fields, function ($field, $key){
            return (isset($field['create']) && $field['create']);
        });

        $this->filters = array_where($this->fields, function ($field, $key){
            return isset($field['filter']) && $field['filter'];
        });

        foreach($this->filters as &$filter){
            if(isset($filter['required']))
                unset($filter['required']);
        }

        if($this->sortable)
            $this->limit = 10000;
    }

    protected function onGetItems(Request $request){}
    protected function onUpdateItem(Request $request, &$item){}

    public function getItems(Request $request)
    {
        if($this->sortable){
            $this->sort_by = 'position';
            $this->sort_dir = 'asc';
        }else{
            $this->sort_by = $request->has('sort_by') ? $request->input('sort_by') : $this->sort_by;
            $this->sort_dir = $request->has('sort_dir') ? $request->input('sort_dir') : $this->sort_dir;
        }
        $items = call_user_func(array($this->class_name, 'orderBy'), $this->sort_by, $this->sort_dir);

        foreach($this->where as $column => $value)
        {
            $items->where($column, '=', $value);
        }
        foreach($this->orWhere as $column => $value)
        {
            $items->orWhere($column, '=', $value);
        }

        foreach($this->where_relation as $model => $id)
        {
            $items->whereHas($model, function ($query) use($id) {
                $query->where('id', $id);
            });
        }

        foreach($this->filters as $filter)
        {
            if($request->has($filter['name']))
            {
                if(in_array($filter['type'], ['text', 'email', 'url', 'textarea'])){
                    $items->where($filter['name'], 'like', '%'.$request->input($filter['name']).'%');
                }
                if(in_array($filter['type'], ['select', 'checkbox'])){
                    $items->where($filter['name'], '=', $request->input($filter['name']));
                }
            }
        }
        $items = $items->paginate($this->limit)->appends($request->except('page'));

        $this->data['items'] = $items;
        $this->data['filters_data'] = $request->all();
        $this->data['sort_query'] = http_build_query($request->except(['page', 'sort_by', 'sort_dir']));

        $this->data['page_name'] = $this->page_name;
        $this->data['route_url'] = $this->route_url;
        $this->data['medias_url'] = $this->medias_url;
        $this->data['global_medias_url'] = $this->global_medias_url;

        $this->data['can_create'] = $this->can_create;
        $this->data['can_delete'] = $this->can_delete;
        $this->data['can_update'] = $this->can_update;

        $this->data['list_title'] = $this->list_title.' ('.(($items->currentPage()-1) * $items->perPage() + 1).' à '.min($items->currentPage() * $items->perPage(), $items->total()).' sur '.$items->total().')';
        $this->data['add_text'] = $this->add_text;
        $this->data['list_help'] = $this->list_help;

        $this->data['sort_by'] = $this->sort_by;
        $this->data['sort_dir'] = $this->sort_dir;
        $this->data['sortable'] = $this->sortable;
        
        $this->data['list_fields'] = $this->list_fields;
        $this->data['create_fields'] = $this->create_fields;
        $this->data['filters'] = $this->filters;

        $this->data['layout_view'] = $this->layout_view;

        $this->onGetItems($request);

        return view($this->list_view, $this->data);
    }

    // Show the form
    public function getCreateItem(Request $request)
    {
        $this->data['item'] = null;

        $this->data['route_url'] = $this->route_url;
        $this->data['page_name'] = $this->page_name;
        $this->data['edit_title'] = $this->add_text;
        $this->data['edit_fields'] = $this->edit_fields;
        $this->data['layout_view'] = $this->layout_view;
        $this->data['edit_help'] = $this->edit_help;

        return view($this->edit_view, $this->data);
    }

    // Store in DB
    public function postStoreItem(Request $request)
    {
        $item = new $this->class_name();
        if($this->sortable){
            $item->position = 0;
            $items = call_user_func(array($this->class_name, 'increment'), 'position');
        }
        foreach($this->defaults as $column => $value)
        {
            $item->$column = $value;
        }
        CrudUtilities::fillItem($request, $item, $this->create_fields);
        CrudUtilities::updateAlias($request, $item, $this->fields);

        return redirect($this->route_url.'/items');
    }

    // Show the form
    public function getEditItem(Request $request)
    {
        $routeParams = Route::current()->parameters();
        $id = $routeParams['id'];
        
        $this->data['item'] = call_user_func(array($this->class_name, 'findOrFail'), $id);
        
        $this->data['route_url'] = $this->route_url;
        $this->data['medias_url'] = $this->medias_url;
        $this->data['global_medias_url'] = $this->global_medias_url;
        $this->data['page_name'] = $this->page_name;
        $this->data['edit_title'] = $this->edit_title;
        $this->data['edit_fields'] = $this->edit_fields;
        $this->data['layout_view'] = $this->layout_view;
        $this->data['edit_help'] = $this->edit_help;

        return view($this->edit_view, $this->data);
    }

    // Update item
    public function postUpdateItem(Request $request)
    {
        $routeParams = Route::current()->parameters();
        $id = $routeParams['id'];

        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);

        CrudUtilities::fillItem($request, $item, $this->edit_fields);
        CrudUtilities::updateAlias($request, $item, $this->fields);

        $this->onUpdateItem($request, $item);

        return redirect($this->route_url.'/items');
    }

    public function postUpdateField(Request $request)
    {
        $id = intVal($request->input('id'));
        $fieldType = $request->input('type');
        $fieldName = $request->input('name');
        $fieldValue = $request->input('value');
        
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        
        if($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'textarea' || $fieldType == 'email' || $fieldType == 'url'
            || $fieldType == 'date' || $fieldType == 'datetime' || $fieldType == 'select'){
            $item->$fieldName = $fieldValue;
        }
        else if($fieldType == 'checkbox'){
            $item->$fieldName = ($fieldValue == 'true');
        }
        else if($fieldType == 'password' && $fieldValue != ''){
            $item->$fieldName = bcrypt($fieldValue);
        }
        else if($fieldType == 'json'){
            $item->$fieldName = json_decode($fieldValue, true);
        }
        CrudUtilities::updateAlias($request, $item, $this->fields);
        
        $item->save();
    }

    public function getDeleteItem(Request $request)
    {
        $routeParams = Route::current()->parameters();
        $id = $routeParams['id'];

        if($this->sortable)
        {
            $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
            $items = call_user_func(array($this->class_name, 'where'), 'position', '>', $item->position);
            $items->decrement('position');
        }
        call_user_func(array($this->class_name, 'destroy'), $id);
        return redirect($this->route_url.'/items');
    }

    public function postUpdatePosition(Request $request)
    {
        $id = intVal($request->input('id'));
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        $oldPos = $item->position;
        
        $beforeItemId = intVal($request->input('beforeItemId'));
        if($beforeItemId != 0){
            $beforeItem = call_user_func(array($this->class_name, 'findOrFail'), $beforeItemId);
            $beforePos = $beforeItem->position;
        }
        else{
            $beforePos = -1; // debut de liste
        }

        if($oldPos < $beforePos){
            $range = [$oldPos + 1, $beforePos];
            $items = call_user_func(array($this->class_name, 'whereBetween'), 'position', $range);
            $items->decrement('position');
            $item->position = $beforePos;
        }
        else if($oldPos > $beforePos + 1){
            $range = [$beforePos + 1, $oldPos - 1];
            $items = call_user_func(array($this->class_name, 'whereBetween'), 'position', $range);
            $items->increment('position');
            $item->position = $beforePos + 1;
        }
        $item->save();
        
        return [
            "item-id" => $id,
            "before-item-id" => $beforeItemId,
            "old-pos" => $oldPos,
            "before-pos" => $beforePos,
        ];
    }
}
