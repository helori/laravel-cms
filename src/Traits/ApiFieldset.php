<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Fieldset;
use Helori\LaravelCms\Models\Field;


trait ApiFieldset
{
    use ApiCrud;
    
    public function update(Request $request, $id)
    {
        $fieldset = Fieldset::findOrFail($id);

        // Update fields
        /*if($request->has('fields'))
        {
            $input_fields = $request->input('fields');
            $input_fields_ids = array_pluck($input_fields, 'id');

            // Delete old fields
            $obsolete_fields_ids = array_where($fieldset->fields->pluck('id')->all(), function($id) use($input_fields_ids){
                return !in_array($id, $input_fields_ids);
            });
            Field::destroy($obsolete_fields_ids);

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
        }*/

        return $this->apiUpdate($request, $id);
    }

    public function delete(Request $request, $id)
    {
        $fieldset = Fieldset::with('fields')->findOrFail($id);
        Field::destroy($fieldset->fields->pluck('id')->all());
        return $this->apiDelete($request, $id);
    }
}
