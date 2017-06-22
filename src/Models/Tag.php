<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Tag extends Model
{
    protected $table = 'tags';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    public function pages()
    {
        return $this->morphedByMany(Page::class, 'taggable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
