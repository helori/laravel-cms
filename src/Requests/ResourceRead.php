<?php

namespace Helori\LaravelCms\Requests;


class ResourceRead extends AdminBase
{
    public function handle($resourceName, $id)
    {
        $apiResource = $this->getResourceApiClass($resourceName);
        $query = $this->queryForResource($resourceName, true);
        $item = $query->findOrFail($id);
        return new $apiResource($item);
    }
}
