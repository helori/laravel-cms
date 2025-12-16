<?php

namespace Helori\LaravelCms\Requests;


class ResourceMediaDelete extends AdminBase
{
    public function handle($resourceName, $resourceId, $mediaId)
    {
        $query = $this->queryForResource($resourceName);
        $resource = $query->findOrFail($resourceId);

        $item = $resource->medias()->findOrFail($mediaId);
        $collection = $item->collection;
        $item->deleteFiles();
        $item->delete();

        $resource->medias()->where('collection', $collection)
            ->orderBy('position', 'asc');

        foreach($resource->medias as $index => $media)
        {
            $media->position = $index;
            $media->save();
        }
    }
}
