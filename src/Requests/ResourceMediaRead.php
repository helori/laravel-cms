<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\Resources\Media as MediaResource;


class ResourceMediaRead extends AdminBase
{
    public function handle($resourceName, $resourceId, $mediaId)
    {
        $classname = $this->getResourceClass($resourceName);
        $resource = $classname::findOrFail($resourceId);
        $item = $resource->medias()->findOrFail($mediaId);
        return new MediaResource($item);
    }
}
