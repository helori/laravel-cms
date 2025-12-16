<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Resources\Media as MediaResource;


class ResourceMediaList extends AdminBase
{
    public function rules()
    {
        return array_merge([
            'collection' => 'sometimes|string|max:255',
        ], $this->listRules());
    }

    public function handle($resourceName, $resourceId)
    {
        $query = $this->queryForResource($resourceName);
        $resource = $query->findOrFail($resourceId);

        $query = $resource->medias();

        if($this->has('collection'))
        {
            $query->where('collection', $this->input('collection'));
        }

        $items = $this->queryList($query);

        return MediaResource::collection($items);
    }
}
