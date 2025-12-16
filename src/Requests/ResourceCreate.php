<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ResourceCreate extends AdminBase
{
    use HasMedia;

    /**
     * Return the field's rules defined in the resource config
     */
    public function rules()
    {
        $resourceName = $this->route()->parameter('resource');
        $config = $this->getResourceConfig($resourceName);
        
        return collect($config['fields'])
            ->pluck('rules', 'name')
            ->filter()
            ->all();
    }

    /**
     * This is called before validation is performed.
     * As input may have been submitted using FormData, 
     * we need to convert 'null' strings to actual null values.
     */
    public function prepareForValidation(): void
    {
        foreach($this->all() as $key => $value)
        {
            if($value === 'null')
            {
                $this->merge([
                    $key => null,
                ]);
            }
        }
    }

    public function handle($resourceName, $id = null)
    {
        $config = $this->getResourceConfig($resourceName);
        $classname = $this->getResourceClass($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $positionField = $config['position'] ?? false;

        $item = new $classname();

        $item->fill($config['defaults'] ?? []);

        if($positionField)
        {
            $nextPosition = $classname::selectRaw('MAX('.$positionField.') + 1 AS position')->first()?->position ?? 0;
            $item->{$positionField} = $nextPosition;
        }

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
                // Set value according to field type
                if($field['type'] === 'integer' || $field['type'] === 'number')
                {
                    $item->{$field['name']} = (int) $this->input($field['name']);
                }
                if($field['type'] === 'boolean')
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
                else if(in_array($field['type'], ['text', 'select', 'email', 'number', 'html', 'textarea', 'date']))
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
