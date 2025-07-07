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

        if($this->has('search') && $this->search){
            $search = $this->search;
            /*$query->where(function($q) use($search){
                $q->where(DB::raw('LOWER(title)'), 'LIKE', '%'.$search.'%');
            });*/
        }

        $items = $this->queryList($query);

        return $apiResource::collection($items);
    }
}
