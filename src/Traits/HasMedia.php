<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Support\Str;
use Helori\LaravelCms\Models\Media;


trait HasMedia
{
    public function medias($collection = null){
        $relationship = $this->morphToMany(Media::class, 'mediable');
        if(!is_null($collection)){
            $relationship->wherePivot('collection', $collection);
        }
        return $relationship;
    }

    public function media($collection = null)
    {
        return $this->medias($collection)->first();
    }

    public function hasMedia($collection = null)
    {
        return ($this->medias($collection)->count() > 0);
    }

    public function syncMedias($medias, $collection){
        $medias_data = [];
        foreach($medias as $media){
            $medias_data[$media['id']] = [
                'collection' => $collection
            ];
        }
        $this->medias($collection)->sync($medias_data);
    }
}
