<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Http\Requests\BaseRequest;
use App\Cms\CmsConfig;


class AdminBase extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function listRules()
    {
        return [
            'search' => 'sometimes|string|nullable',
            'order_by' => 'sometimes|string|nullable',
            'order_dir' => 'sometimes|string|in:asc,desc',
            'limit' => 'sometimes|integer',
        ];
    }

    /**
     * Return the list results of a crud READ request, working on an existing query.
     *
     * @return mixed
     */
    protected function queryList($query)
    {
        if(empty($query->getQuery()->orders))
        {
            $tableName = $query->getModel()->getTable();
            $orderBy = $tableName.'.id';
            // If order_by is sent, use it as is (table name must be prepended if necessary) :
            if($this->has('order_by')){
                $orderBy = $this->order_by;
            }

            $orderDir = $this->has('order_dir') ? $this->order_dir : 'asc';
            $query->orderBy($orderBy, $orderDir);
            //$query->orderByRaw($orderBy, $orderDir);
            //$query->orderByRaw($orderBy.' '.$orderDir.' NULLS LAST');
            //$query->orderByRaw('ISNULL('.$orderBy.'), '.$orderBy.' '.$orderDir);
        }

        $limit = intVal($this->input('limit', 10));

        //dump($query->toSql());

        return ($limit === 0) ? $query->get() : $query->paginate($limit);
    }

    protected function getResourceConfig(string $resourceName): array
    {
        $resourcesConfig = collect(CmsConfig::resources());
        return $resourcesConfig->firstWhere('name', $resourceName);
    }

    protected function getResourceFields(string $resourceName): array
    {
        return $this->getResourceConfig($resourceName)['fields'];
    }

    protected function getResourceClass(string $resourceName): string
    {
        $config = $this->getResourceConfig($resourceName);
        return $config['classname'];
    }

    protected function getResourceApiClass(string $resourceName): string
    {
        $config = $this->getResourceConfig($resourceName);
        return $config['resource_classname'];
    }

    protected function queryForResource(string $resourceName, bool $withMedias = false)
    {
        $query = $this->getResourceClass($resourceName)::query();
        $config = $this->getResourceConfig($resourceName);
        
        $whereClauses = $config['query'] ?? [];
        if(!empty($whereClauses)){
            $query->where($whereClauses);
        }

        $positionField = $config['position'] ?? null;
        if($positionField)
        {
            $query->orderBy($positionField, 'asc');
        }

        if($withMedias)
        {
            $fields = $config['fields'];
            foreach($fields as $field)
            {
                if(in_array($field['type'], ['media', 'medias']))
                {
                    $query->with($field['name']);
                }
            }
        }

        return $query;
    }
}
