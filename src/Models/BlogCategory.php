<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class BlogCategory extends Model
{
	protected $table = 'blog_categories';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    public function articles(){
    	return $this->belongsToMany(BlogArticle::class, 'blog_article_category');
    }
}
