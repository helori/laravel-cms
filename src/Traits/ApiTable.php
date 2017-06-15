<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Requests\Table as TableRequest;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Table;
use Helori\LaravelCms\Models\Field;


trait ApiTable
{
    use ApiCrud;

    public function create(TableRequest $request)
    {
        $request->modifyInput();

        $name = $request->input('name', null);
        $alias = $request->input('alias', null);
        $table_name = $request->input('table', null);

        if(Schema::hasTable($table_name)){
            return response()->json([
                'message' => "La table ".$table_name." existe déjà dans la base de données"
            ], 422);
        }

        try{
            Schema::create($table_name, function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }catch(\Exception $e){
            return response()->json([
                'message' => "La table ".$table_name." n'a pas pu être créée : ".$e->getMessage()
            ], 422);
        }

        return $this->apiCreate($request);
    }
    
    public function update(TableRequest $request, $id)
    {
        $request->modifyInput();

        // Rename table in DB if needed
        $table_name = $request->input('table');
        $t = Table::findOrFail($id);
        if($t->table != $table_name){
            Schema::rename($t->table, $table_name);
        }

        // Update fields
        if($request->has('fields'))
        {
            $input_fields = $request->input('fields');
            $input_fields_ids = array_pluck($input_fields, 'id');

            // Delete old fields
            foreach($t->fields as $field){
                if(!in_array($field->id, $input_fields_ids)){
                    Schema::table($table_name, function (Blueprint $table) use($field) {
                        $table->dropColumn([$field->name]);
                        $field->delete();
                    });
                }
            }

            foreach($input_fields as $input_field)
            {
                if(!isset($input_field['default'])){
                    $input_field['default'] = null;
                }
                if(!isset($input_field['title'])){
                    $input_field['title'] = '';
                }
                if(!isset($input_field['position'])){
                    $input_field['position'] = false;
                }
                if(!isset($input_field['create'])){
                    $input_field['create'] = false;
                }
                if(!isset($input_field['list'])){
                    $input_field['list'] = false;
                }
                if(!isset($input_field['options'])){
                    $input_field['options'] = [];
                }

                // Create missing fields
                if(!isset($input_field['id']))
                {
                    // Create field in the "fields" table
                    $field = new Field();
                    $field->table_id = $t->id;
                    $field->type = $input_field['type'];
                    $field->name = $input_field['name'];
                    $field->title = $input_field['title'];
                    $field->default = $input_field['default'];
                    $field->position = $input_field['position'];
                    $field->create = $input_field['create'];
                    $field->list = $input_field['list'];
                    $field->options = $input_field['options'];
                    $field->save();

                    try{
                        // Create field in target table
                        Schema::table($table_name, function (Blueprint $table) use($field) {

                            $type = $field->type;
                            $name = $field->name;
                            $default = $field->default;

                            if(in_array($type, ['text', 'email', 'url', 'select'])){
                                $table->string($name)->nullable()->default($default);
                            }
                            else if(in_array($type, ['textarea', 'editor'])){
                                if($default){
                                    $table->text($name)->nullable()->default($default);
                                }else{
                                    $table->text($name)->nullable();
                                }
                            }
                            else if(in_array($type, ['checkbox'])){
                                $table->boolean($name)->nullable()->default($default ? true : false);
                            }
                            else if(in_array($type, ['date'])){
                                $table->date($name)->nullable()->default($default);
                            }
                            else if(in_array($type, ['media', 'medias'])){
                                // Nothing to do !
                            }
                        });
                    }catch(\Exception $e){
                        return response()->json([
                            'message' => "Le champ '".$field->name."' de type '".$field->type."' de la table '".$table_name."' n'a pas pu être créé : ".$e->getMessage()
                        ], 422);
                    }
                }
                // Update existing fields
                else
                {
                    $field = Field::findOrFail($input_field['id']);

                    $type = $input_field['type'];
                    $name = $input_field['name'];
                    $title = $input_field['title'];
                    $default = $input_field['default'];
                    $position = $input_field['position'];
                    $create = $input_field['create'];
                    $list = $input_field['list'];
                    $options = $input_field['options'];

                    // Rename column if needed
                    if($field->name != $name)
                    {
                        try{
                            Schema::table($table_name, function (Blueprint $table) use($field, $name) {
                                $table->renameColumn($field->name, $name);
                            });
                        }catch(\Exception $e){
                            return response()->json([
                                'message' => "Le champ '".$field->name."' de la table '".$table_name."' n'a pas pu être renommé en '".$name."' : ".$e->getMessage()
                            ], 422);
                        }
                    }

                    // Update field in table (using old data from "fields")
                    if($field->type != $type || $field->default != $default)
                    {
                        try{
                            Schema::table($table_name, function (Blueprint $table) use($type, $name, $default) {
                                if(in_array($type, ['text', 'email', 'url', 'select'])){
                                    $table->string($name)->nullable()->default($default)->change();
                                }
                                else if(in_array($type, ['textarea', 'editor'])){
                                    if($default){
                                        $table->text($name)->nullable()->default($default)->change();
                                    }else{
                                        $table->text($name)->nullable()->change();
                                    }
                                }
                                else if(in_array($type, ['checkbox'])){
                                    $table->boolean($name)->default($default ? true : false)->change();
                                }
                                else if(in_array($type, ['date'])){
                                    $table->date($name)->nullable()->default($default)->change();
                                }
                                else if(in_array($type, ['media', 'medias'])){
                                    // Nothing to do !
                                }
                            });
                        }catch(\Exception $e){
                            return response()->json([
                                'message' => "Le champ '".$name."' de type '".$field->type."' de la table '".$table_name."' n'a pas pu être mis à jour vers le type '".$type."' avec la valeur par défaut '".$default."' : ".$e->getMessage()
                            ], 422);
                        }
                    }

                    // Update "fields" table
                    $field->type = $type;
                    $field->name = $name;
                    $field->title = $title;
                    $field->default = $default;
                    $field->position = $position;
                    $field->create = $create;
                    $field->list = $list;
                    $field->options = $options;
                    $field->save();
                }
            }
        }

        return $this->apiUpdate($request, $id);
    }

    public function delete(Request $request, $id)
    {
        $table = Table::with('fields')->findOrFail($id);
        Field::destroy($table->fields->pluck('id')->all());
        Schema::dropIfExists($table->table);

        return $this->apiDelete($request, $id);
    }
}
