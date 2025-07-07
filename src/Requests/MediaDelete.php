<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Models\Media;


class MediaDelete extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);
        $item->deleteFiles();
        $item->delete();
    }
}
