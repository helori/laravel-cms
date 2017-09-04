<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Models\Media;
use Helori\LaravelCms\Traits\HasMedia;


class BlogArticle extends Model
{
    use HasMedia;

	protected $table = 'blog_articles';
    protected $dates = ['created_at', 'updated_at', 'published_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function categories(){
    	return $this->belongsToMany(BlogCategory::class, 'blog_article_category');
    }

    public function image(){
    	return $this->morphToMany(Media::class, 'mediable')->wherePivot('collection', 'image');
    }

    public function images(){
    	return $this->morphToMany(Media::class, 'mediable')->wherePivot('collection', 'images');
    }
}
