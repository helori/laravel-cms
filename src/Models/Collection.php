<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Collection extends Model
{
    use Searchable;

    protected $table = 'collections';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [
        
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function pages(){
        return $this->belongsToMany(Page::class);
    }
}
