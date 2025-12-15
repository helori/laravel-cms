<?php

namespace Helori\LaravelCms\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Media extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

            'filepath' => $this->filepath,
            'filename' => $this->filename,
            'extension' => $this->extension,
            'title' => $this->title,
            'type' => $this->type,
            'mime' => $this->mime,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'decache' => $this->decache,
            'position' => $this->position,

            'url' => $this->filepath ? Storage::url($this->filepath) : null,
        ];
    }
}
