<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\Fieldset;
use Helori\LaravelCms\Requests\FieldsetCreate as FieldsetCreateRequest;
use Helori\LaravelCms\Requests\FieldsetUpdate as FieldsetUpdateRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class FieldsetsController extends Controller
{
    public function read(Request $request, $id = null)
    {
        $query = Fieldset::with(['fields']);

        if(!is_null($id)){

            return $query->findOrFail($id);

        }else{
            
            return $query->orderBy('title', 'asc')->get();
        }
    }

    public function delete(Request $request, $id)
    {
        $item = Fieldset::findOrFail($id);

        try{
            Schema::dropIfExists($item->table);
        }catch(\Exception $e){
            abort(500, 'Ouch! the table "'.$item->table.'" could not be deleted.');
        }

        $item->delete();
    }

    public function create(FieldsetCreateRequest $request)
    {
        $request->modifyInput();

        $table_name = $request->input('table');

        try{
            Schema::create($table_name, function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }catch(\Exception $e){
            abort(500, 'Ouch! the table "'.$table_name.'" could not be created in your database. Maybe you already have a fieldset with a title leading to the same table name?');
        }

        $item = Fieldset::create($request->all());

        return $item;
    }

    public function update(FieldsetUpdateRequest $request, $id)
    {
        $request->modifyInput();

        $item = Fieldset::findOrFail($id);

        $table_name = $request->input('table');
        
        if($table_name !== $item->table){
            try{
                Schema::rename($item->table, $table_name);
            }catch(\Exception $e){
                abort(500, 'Ouch! the table "'.$item->table.'" could not be renamed to "'.$table_name.'". Maybe you already have a fieldset with a title leading to the same table name?');
            }
        }
        
        $item->update($request->except(['fields']));

        $item->save();

        return Fieldset::with(['fields'])->findOrFail($item->id);
    }
}
