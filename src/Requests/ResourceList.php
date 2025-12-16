<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;


class ResourceList extends AdminBase
{
    public function rules()
    {
        return $this->listRules();
    }

    public function handle($resourceName)
    {
        $config = $this->getResourceConfig($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $query = $this->queryForResource($resourceName, true);

        $searchConfig = $config['table']['searchable'] ?? [];
        if($this->has('search') && $this->search && !empty($searchConfig)){
            $query->where(function($q) use($searchConfig){
                foreach($searchConfig as $fieldName){
                    $q->orWhere(DB::raw('LOWER('.$fieldName.')'), 'LIKE', '%'.strtolower($this->search).'%');
                }
            });
        }

        $filters = $config['table']['filters'] ?? [];
        foreach($filters as $filter)
        {
            if($this->has($filter['field'])){
                $value = $this->input($filter['field'], null);
                if(!is_null($value)){
                    $query->where($filter['field'], $value);
                }
            }
        }

        $items = $this->queryList($query);

        return $apiResource::collection($items);
    }
}
