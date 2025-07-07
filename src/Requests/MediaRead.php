<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\Resources\Media as MediaResource;


class MediaRead extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);
        return new MediaResource($item);
    }
}
