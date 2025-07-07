<?php

namespace App\Http\Requests\Admin;


class ResourceUpdate extends ResourceCreate
{
    public function handle($resourceName, $id = null)
    {
        $classname = $this->getResourceClass($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        $item = $classname::findOrFail($id);

        $this->setInput($item, $resourceName);

        return new $apiResource($item);
    }
}
