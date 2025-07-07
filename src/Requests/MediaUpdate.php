<?php

namespace Helori\LaravelCms\Requests;

use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\Resources\Media as MediaResource;


class MediaUpdate extends AdminBase
{
    public function rules()
    {
        return [

        ];
    }

    public function handle()
    {
        $item = Media::findOrFail($id);



        return new MediaResource($item);
    }
}
