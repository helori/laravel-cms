<?php

namespace Helori\LaravelCms\Requests;

use App\Models\Media;
use App\Http\Resources\Media as MediaResource;


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
