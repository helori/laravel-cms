<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\resources\Media as MediaResource;


class MediaRead extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);
        return new MediaResource($item);
    }
}
