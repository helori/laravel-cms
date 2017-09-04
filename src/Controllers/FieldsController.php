<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\Fieldset;
use Helori\LaravelCms\Models\Field;
use Helori\LaravelCms\Models\Element;
use Helori\LaravelCms\Requests\FieldCreate as FieldCreateRequest;
use Helori\LaravelCms\Requests\FieldUpdate as FieldUpdateRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class FieldsController extends Controller
{
    public function read(Request $request, $fieldsetId, $id = null)
    {
        $query = Fieldset::findOrFail($fieldsetId)->fields();

        if(!is_null($id)){

            return $query->findOrFail($id);

        }else{

            return $query->orderBy('created_at', 'asc')->get();
        }
    }

    public function delete(Request $request, $fieldsetId, $id)
    {
        $fieldset = Fieldset::findOrFail($fieldsetId);

        $item = $fieldset->fields()->findOrFail($id);

        if($item->type !== 'media'){
            try{
                
                Schema::table($fieldset->table, function (Blueprint $table) use($fieldset, $item) {

                    $table->dropColumn($item->name);

                });

            }catch(\Exception $e){

                abort(500, 'Ouch! the field "'.$item->name.'" could not be deleted from your database table "'.$fieldset->table.'".');
            }
        }else{
            $element = new Element();
            $element->setTable($fieldset->table);
            $element->medias()->detach();
        }

        $item->delete();
    }

    public function create(FieldCreateRequest $request, $fieldsetId)
    {
        $request->modifyInput();

        $fieldset = Fieldset::findOrFail($fieldsetId);

        $data = $request->all();

        $type = $this->getDatabaseFieldType($data['type']);

        try{
            
            Schema::table($fieldset->table, function (Blueprint $table) use($fieldset, $data, $type) {

                if($type === 'string'){

                    $table->string($data['name'])->nullable()->default($data['default'] ?: null);

                }else if($type === 'text'){

                    $table->text($data['name'])->nullable();

                }else if($type === 'boolean'){

                    $table->boolean($data['name'])->nullable()->default($data['default'] ? true : false);

                }
            });

        }catch(\Exception $e){

            abort(500, 'Ouch! the field "'.$data['name'].'" could not be created in your database table "'.$fieldset->table.'". Maybe this field name is already used in your table?');
        }

        $item = new Field();
        $item->fieldset_id = $fieldsetId;
        $item->fill($request->all());
        $item->save();

        return $item;
    }

    public function update(FieldUpdateRequest $request, $fieldsetId, $id)
    {
        $request->modifyInput();

        $fieldset = Fieldset::findOrFail($fieldsetId);

        $item = $fieldset->fields()->findOrFail($id);

        $data = $request->all();

        $old_type = $this->getDatabaseFieldType($item->type);
        $new_type = $this->getDatabaseFieldType($data['type']);

        if($old_type != $new_type || $data['default'] != $item->default){

            try{
                
                Schema::table($fieldset->table, function (Blueprint $table) use($fieldset, $item, $data, $new_type) {

                    if($new_type === 'string'){

                        $table->string($item->name)->nullable()->default($data['default'] ?: null)->change();

                    }else if($new_type === 'text'){

                        $table->text($item->name)->nullable()->default(null)->change();

                    }else if($new_type === 'boolean'){

                        $table->boolean($item->name)->nullable()->default($data['default'] ? true : false)->change();

                    }else if($new_type === 'media'){

                        $table->dropColumn($item->name);

                    }

                });

            }catch(\Exception $e){

                abort(500, 'Ouch! the field "'.$item->name.'" of type '.$item->type.' (default: '.$item->default.') could not be updated to type '.$data['type'].' (default: '.$data['default'].') in your database table "'.$fieldset->table.'".');
            }
        }

        if($data['name'] != $item->name){

            try{
                
                Schema::table($fieldset->table, function (Blueprint $table) use($fieldset, $item, $data, $new_type) {

                    if($new_type !== 'media'){

                        $table->renameColumn($item->name, $data['name']);
                        
                    }

                });

            }catch(\Exception $e){

                abort(500, 'Ouch! the field "'.$item->name.'" could not be renamed to "'.$data['name'].'" in your database table "'.$fieldset->table.'". Maybe this field name is already used in your table?'.$e->getMessage());
            }

        }

        $item->update($request->all());

        $item->save();

        return $item;
    }

    protected function getDatabaseFieldType($type){

        if(in_array($type, ['text', 'email', 'url', 'phone', 'password'])){

            return 'string';

        }else if(in_array($type, ['textarea', 'editor', 'json'])){

            return 'text';

        }else if(in_array($type, ['checkbox'])){

            return 'boolean';

        }else if(in_array($type, ['media'])){

            return 'media';

        }else{

            abort(422, 'Unknown field type : '.$type);

        }

    }
}
