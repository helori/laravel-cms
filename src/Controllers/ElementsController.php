<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\Fieldset;
use Helori\LaravelCms\Models\Element;


class ElementsController extends Controller
{
    public function read(Request $request, $fieldsetId, $id = null)
    {
        $fieldset = Fieldset::findOrFail($fieldsetId);

        $element = new Element;
        $element->setTable($fieldset->table);

        if($fieldset->single){

            if($element->count() === 0){
                $element->save();
            }
            return $element->firstOrFail();
            
        }else if(!is_null($id)){

            return $element->findOrFail($id);

        }else{

            return $element->orderBy('created_at', 'asc')->get();
        }
    }

    public function delete(Request $request, $fieldsetId, $id)
    {
        $fieldset = Fieldset::findOrFail($fieldsetId);

        $element = new Element;
        
        $element->setTable($fieldset->table);

        $element->medias()->detach();

        $element->findOrFail($id)->delete();
    }

    public function create(Request $request, $fieldsetId)
    {
        $fieldset = Fieldset::findOrFail($fieldsetId);

        $element = new Element;
        $element->setTable($fieldset->table);

        $element->fill($request->all());
        $element->save();

        return $element;
    }

    public function update(Request $request, $fieldsetId, $id)
    {
        $fieldset = Fieldset::findOrFail($fieldsetId);

        $relations = [];
        foreach($fieldset->fields as $field){
            if($field->type == 'media'){
                $relations[] = $field->name;
            }
        }

        $element = new Element;
        
        $element->setTable($fieldset->table);

        $element = $element->findOrFail($id);

        $element->fill($request->except($relations));

        $element->save();

        foreach($relations as $relation){
            $medias = $request->input($relation);
            $element->syncMedias($medias, $relation);
        }

        return $element;
    }
}
