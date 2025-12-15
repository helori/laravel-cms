<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Resources\Media as MediaResource;


class ResourceMediaCreate extends AdminBase
{
    public function handle($resourceName, $resourceId)
    {
        $classname = $this->getResourceClass($resourceName);
        $resource = $classname::findOrFail($resourceId);

        $fields = $this->getResourceFields($resourceName);
        $apiResource = $this->getResourceApiClass($resourceName);

        //$query = $classname::medias();

        // TODO...

        return new MediaResource($item);
    }
}
