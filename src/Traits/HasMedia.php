<?php

namespace Helori\LaravelCms\Traits;

use Helori\LaravelCms\Relations\MorphToMany;
use Illuminate\Support\Str;
use Helori\LaravelCms\Models\Media;


trait HasMedia
{
    // Polymorphic relation
    public function medias()
    {
        /*$name = 'mediable';

        $caller = $this->guessBelongsToManyRelation();

        // First, we will need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we will make the query
        // instances, as well as the relationship instances we need for these.
        $instance = $this->newRelatedInstance(Media::class);

        $foreignKey = $name.'_id';

        $relatedKey = $instance->getForeignKey();

        // Now we're ready to create a new query builder for this related model and
        // the relationship instances for this relation. This relations will set
        // appropriate query constraints then entirely manages the hydrations.
        $table = Str::plural($name);

        $morphToMany = new MorphToMany(
            $instance->newQuery(), $this, $name, $table,
            $foreignKey, $relatedKey, $caller, false, $this->table
        );

        //$morphToMany->setMorphClass('articles');

        return $morphToMany->withPivot('collection', 'position');*/

        return $this->morphToMany(Media::class, 'mediable')->withPivot('collection', 'position');
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
        return $this->medias()->wherePivot('collection', $collection)->first();
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
        return $this->medias()->wherePivot('collection', $collection)->get(); //->orderBy('pivot.position', 'asc')->get();
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
