<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\Resources\Media as MediaResource;


class ResourceMediaUpdate extends AdminBase
{
    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
        ];
    }

    public function handle($resourceName, $resourceId, $mediaId)
    {
        $classname = $this->getResourceClass($resourceName);
        $resource = $classname::findOrFail($resourceId);

        $item = $resource->medias()->findOrFail($mediaId);

        if($this->has('title'))
        {
            $title = $this->input('title');
            $fileslug = Str::slug($item->id.'-'.$title, '-');
            $filename = $fileslug.'.'.$item->extension;
            $filepath = 'medias/'.$filename;
            $storage = Storage::disk('public');

            if(!$storage->exists($item->filepath)){
                abort(404, 'File does not exist at path: '.$item->filepath);
            }

            $storage->move($item->filepath, $filepath);

            $item->update([
                'title' => $title,
                'filename' => $filename,
                'filepath' => $filepath,
                'decache' => now(),
            ]);
        }

        return new MediaResource($item);
    }
}
