<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Facades\DB;


class ResourceList extends AdminBase
{
    public function rules()
    {
        return $this->listRules();
    }

    public function handle($resourceName)
    {
        $fields = $this->getResourceFields($resourceName);
        $classname = $this->getResourceClass($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $query = $classname::query();

        foreach($fields as $field)
        {
            if(in_array($field['type'], ['media', 'medias']))
            {
                $query->with($field['name']);
            }
        }

        $tableConfig = $this->getResourceConfig($resourceName)['table']['searchable'] ?? [];

        if($this->has('search') && $this->search && !empty($tableConfig)){
            $query->where(function($q) use($tableConfig){
                foreach($tableConfig as $fieldName){
                    $q->orWhere(DB::raw('LOWER('.$fieldName.')'), 'LIKE', '%'.strtolower($this->search).'%');
                }
            });
        }

        $items = $this->queryList($query);

        return $apiResource::collection($items);
    }
}
