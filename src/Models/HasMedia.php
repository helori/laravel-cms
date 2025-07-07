<?php

namespace App\Models;

trait HasMedia
{
    // Polymorphic relation
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
