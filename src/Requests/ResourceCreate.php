<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class ResourceCreate extends AdminBase
{
    use HasMedia;

    public function rules()
    {
        $resourceName = $this->route()->parameter('resource');
        $fields = $this->getResourceFields($resourceName);
        $rules = [];

        foreach($fields as $field)
        {
            if(isset($field['rules'])){
                $rules[$field['name']] = $field['rules'];
            }
        }

        return $rules;
    }

    public function handle($resourceName, $id = null)
    {
        $classname = $this->getResourceClass($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $item = new $classname();
        $item->save();

        $this->setInput($item, $resourceName);

        return new $apiResource($item);
    }

    protected function setInput(&$item, $resourceName)
    {
        $fields = $this->getResourceFields($resourceName);

        foreach($fields as $field)
        {
            if($this->has($field['name']))
            {
                if($this->input($field['name']) === 'null')
                {
                    $item->{$field['name']} = null;
                }
                else if($field['type'] === 'boolean')
                {
                    $item->{$field['name']} = $this->boolean($field['name']);
                }
                else if($field['type'] === 'date')
                {
                    $item->{$field['name']} = $this->input($field['name']);
                }
                else if($field['type'] === 'slug')
                {
                    $item->{$field['name']} = Str::slug($this->input($field['name']), '-');
                }
                else if($field['type'] === 'password' && $this->filled($field['name']))
                {
                    $item->{$field['name']} = Hash::make($this->input($field['name']));
                }
                else if(in_array($field['type'], ['text', 'email', 'number', 'html', 'textarea', 'date']))
                {
                    $item->{$field['name']} = $this->input($field['name']);
                }
            }

            if($field['type'] === 'media')
            {
                $this->attachMedia($item, $field['name']);
            }
            else if($field['type'] === 'medias')
            {
                $this->attachMedias($item, $field['name']);
            }
        }

        $item->save();
    }
}
