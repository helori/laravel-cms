<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Facades\Storage;
use Helori\LaravelCms\Models\Media;


class MediaDownload extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);

        $storage = Storage::disk('public');
        $content = $storage->get($item->filepath);

        return response()->make($content, 200, [
            'Content-Type' => $item->mime,
            'Content-Disposition' => 'inline; filename="'.$item->filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        ]);
    }
}
