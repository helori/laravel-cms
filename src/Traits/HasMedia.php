<?php

namespace Helori\LaravelCms;

trait HasMedia
{
    // Polymorphic relation
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // --------------------------------------------------------------
    //  Single Media
    // --------------------------------------------------------------
    public function hasMedia($collection)
    {
        return $this->getMedia($collection) != false;
    }

    public function mediaExists($collection)
    {
        $media = $this->getMedia($collection);
        return ($media && is_file($media->filepath));
    }

    public function getMedia($collection)
    {
        return $this->medias()->where('collection', $collection)->first();
    }

    public function mediaPath($collection)
    {
        $media = $this->getMedia($collection);
        if($media){
            return $media->filepath;
        }
    }

    public function mediaUrl($collection)
    {
        return asset($this->mediaPath($collection));
    }

    public function mediaUrlDecached($collection)
    {
        $media = $this->getMedia($collection);
        if($media && is_file($media->filepath)){
            return asset($media->filepath.'?'.filemtime($media->filepath));
        }
    }

    // --------------------------------------------------------------
    //  Multiple Medias
    // --------------------------------------------------------------
    public function getMedias($collection)
    {
        return $this->medias()->where('collection', $collection)->orderBy('position', 'asc')->get();
    }

    public function mediasExists($collection, $idx)
    {
        $medias = $this->getMedias($collection);
        return isset($medias[$idx]) && is_file($medias[$idx]->filepath);
    }

    public function hasMedias($collection)
    {
        return count($this->getMedias($collection)) > 0;
    }

    public function mediasPath($collection)
    {
        $medias = $this->getMedias($collection);
        $paths = [];
        foreach($medias as $media){
            if(is_file($media->filepath)){
                $paths[] = $media->filepath;
            }
        }
        return $paths;
    }

    public function mediasUrl($collection)
    {
        $medias = $this->getMedias($collection);
        $urls = [];
        foreach($medias as $media){
            if(is_file($media->filepath)){
                $urls[] = url($media->filepath.'?'.filemtime($media->filepath));
            }
        }
        return $paths;
    }
}
