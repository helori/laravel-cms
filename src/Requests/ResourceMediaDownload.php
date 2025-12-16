<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Facades\Storage;


class ResourceMediaDownload extends AdminBase
{
    public function handle($resourceName, $resourceId, $mediaId)
    {
        $query = $this->queryForResource($resourceName);
        $resource = $query->findOrFail($resourceId);

        $item = $resource->medias()->findOrFail($mediaId);

        $storage = Storage::disk('public');
        $content = $storage->get($item->filepath);

        return response()->make($content, 200, [
            'Content-Type' => $item->mime,
            'Content-Disposition' => 'inline; filename="'.$item->filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        ]);
    }
}
