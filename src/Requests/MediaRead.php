<?php

namespace Helori\LaravelCms\Requests;

use App\Models\Media;
use App\Http\Resources\Media as MediaResource;


class MediaRead extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);
        return new MediaResource($item);
    }
}
