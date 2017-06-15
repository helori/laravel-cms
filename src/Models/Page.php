<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Traits\HasMedia;

class Page extends Model
{
    use HasMedia;

    protected $table = 'pages';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [

    ];

    protected $appends = [
        'pages',
        'collections',
        'image',
        'images'
    ];

    public function collections(){
        return $this->belongsToMany(Collection::class);
    }

    public function pages(){
        return $this->hasMany(Page::class, 'parent_id', 'id')->orderBy('position');
    }
    
    public function page(){
        return $this->belongsTo(Page::class, 'id', 'parent_id');
    }

    public function getPagesAttribute(){
        return $this->pages()->get();
    }

    public function getCollectionsAttribute(){
        return $this->collections()->get();
    }

    public function getImageAttribute(){
        return $this->getMedias('image');
    }

    public function getImagesAttribute(){
        return $this->getMedias('images');
    }
}
