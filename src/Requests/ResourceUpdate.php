<?php

namespace Helori\LaravelCms\Requests;


class ResourceUpdate extends ResourceCreate
{
    public function handle($resourceName, $id = null)
    {
        $apiResource = $this->getResourceApiClass($resourceName);
        $query = $this->queryForResource($resourceName, true);
        $item = $query->findOrFail($id);

        $this->setInput($item, $resourceName);

        return new $apiResource($item);
    }
}
