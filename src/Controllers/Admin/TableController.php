<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Models\Table;
use Helori\LaravelCms\Models\Field;
use Helori\LaravelCms\Models\Element;


class TableController extends Controller
{
    public function table(Request $request, $table_id)
    {
    	$this->data['table_id'] = $table_id;
        return view('laravel-cms::admin.table', $this->data);
    }

    public function loadMedias(&$item, $table_id){

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

    	$element = new Element($table->table);

    	foreach($table->fields as $field){
            if(!in_array($field->type, ['media', 'medias'])){
        		$value = $request->input($field->name);
        		$element->{$field->name} = $value;
        		$element->save();
            }
    	}

		return $element;
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

    	$fields = [];
    	foreach($table->fields as $field){
            if(!in_array($field->type, ['media', 'medias'])){
                $fields[$field->name] = $request->input($field->name);
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

    	DB::table($table->table)->where('id', $id)->update($fields);

    	return $this->read($request, $table_id, $id);
    }
}
