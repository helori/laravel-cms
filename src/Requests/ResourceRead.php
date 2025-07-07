<?php

namespace Helori\LaravelCms\Requests;


class ResourceRead extends AdminBase
{
    public function handle($resourceName, $id)
    {
        $classname = $this->getResourceClass($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $item = $classname::findOrFail($id);

        return new $apiResource($item);
    }
}
