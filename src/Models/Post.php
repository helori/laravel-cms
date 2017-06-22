<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Traits\HasMedia;
//use Laravel\Scout\Searchable;


class Post extends Model
{
	use hasMedia; //, Searchable;

    protected $table = 'posts';
    protected $dates = ['created_at', 'updated_at', 'date'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [

    ];

    protected $appends = [
        'image',
        'images'
    ];
    
    public function collection(){
        return $this->belongsTo(Table::class);
    }
    
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getImageAttribute(){
        return $this->getMedias('image');
    }
    
    public function getImagesAttribute(){
        return $this->getMedias('images');
    }
}
