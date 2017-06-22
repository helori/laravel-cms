<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Table;
use Helori\LaravelCms\Models\Field;



class ElementsController extends Controller
{
    // -------------------------------------------------------------
    //  View
    // -------------------------------------------------------------
    public function element(Request $request, $table_id)
    {
        $this->init();
    	$this->data['table_id'] = $table_id;
        return view('laravel-cms::admin.element', $this->data);
    }

    public function edit(Request $request, $table_id, $id)
    {
        $this->init();
        $this->data['table_id'] = $table_id;
        $this->data['id'] = $id;
        return view('laravel-cms::admin.element-edit', $this->data);
    }

    // -------------------------------------------------------------
    //  API
    // -------------------------------------------------------------
    public function read(Request $request, $table_id, $id = null)
    {
        $table = Table::findOrFail($table_id);

    	if($id){
            $item = DB::table($table->table)->where('id', $id)->first();
            $this->loadMedias($item, $table_id);
            return (array) $item;
    	}else{
            $items = DB::table($table->table)->get();
            foreach($items as &$item){
                $this->loadMedias($item, $table_id);
            }
            return $items;
    	}
    }

    public function create(Request $request, $table_id)
    {
    	$table = Table::with(['fields' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->findOrFail($table_id);

    	$data = [];

    	foreach($table->fields as $field){
            if($field->create){
                $data[$field->name] = $request->input($field->name);
            }
    	}
        $id = DB::table($table->table)->insertGetId($data);
        
        $item = $this->read($request, $table_id, $id);
        $this->updateAlias($table, $item);

		return $this->read($request, $table_id, $id);
    }
    
    public function delete(Request $request, $table_id, $id)
    {
    	$table = Table::findOrFail($table_id);
    	DB::table($table->table)->delete($id);
    }

    public function update(Request $request, $table_id, $id)
    {
    	$table = Table::with(['fields' => function ($query) {
            $query->orderBy('position', 'asc');
        }])->findOrFail($table_id);

    	$data = [];

    	foreach($table->fields as $field){
            if(!in_array($field->type, ['media', 'medias'])){
                $data[$field->name] = $request->input($field->name);
            }else{
                $medias = $request->input($field->name);
                $medias = array_pluck($medias, 'id');

                DB::table('mediables')->where([
                    'mediable_id' => $id,
                    'mediable_type' => $table->table,
                    'collection' => $field->name
                ])->delete();

                foreach($medias as $media_id){
                    DB::table('mediables')->insert([
                        'mediable_id' => $id,
                        'mediable_type' => $table->table,
                        'collection' => $field->name,
                        'media_id' => $media_id
                    ]);
                }
            }
    	}
        DB::table($table->table)->where('id', $id)->update($data);

        $item = $this->read($request, $table_id, $id);
        $this->updateAlias($table, $item);

    	return $this->read($request, $table_id, $id);
    }

    // -------------------------------------------------------------
    //  Utilities
    // -------------------------------------------------------------
    protected function loadMedias(&$item, $table_id){

        $table = Table::with(['fields' => function ($query) {
            $query->where('type', 'media')->orWhere('type', 'medias');
        }])->findOrFail($table_id);

        $collections = $table->fields->pluck('name');

        if($item){
            foreach($collections as $collection){
                $item->{$collection} = DB::table('mediables')->where([
                    'mediable_id' => $item->id,
                    'mediable_type' => $table->table,
                    'collection' => $collection
                ])->leftJoin('medias', 'medias.id', '=', 'mediables.media_id')->get();
            }
        }
    }

    protected function updateAlias(&$table, &$item)
    {
        
        foreach($table->fields as $field){
            if($field->type == 'alias'){
                $alias = Str::slug($item['id'].'-'.$item[$field->default], '-');
                DB::table($table->table)->where('id', $item['id'])->update([
                    $field->name => $alias
                ]);
            }
        }
    }
}
