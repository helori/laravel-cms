<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Traits\HasMedia;
//use Laravel\Scout\Searchable;


class Page extends Model
{
    use HasMedia; //, Searchable;

    protected $table = 'pages';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [
        
    ];

    protected $appends = [
        'pages',
        'collections',
        'tags',
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

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function hasTag(string $tagTitle){
        foreach($this->tags as $tag){
            if($tag->title == $tagTitle){
                return true;
            }
        }
        return false;
    }

    public function getPagesAttribute(){
        return $this->pages()->get();
    }

    public function getCollectionsAttribute(){
        return $this->collections()->get();
    }

    public function getTagsAttribute(){
        return $this->tags()->get();
    }

    public function getImageAttribute(){
        return $this->getMedias('image');
    }

    public function getImagesAttribute(){
        return $this->getMedias('images');
    }
}
