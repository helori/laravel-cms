<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Traits\HasMedia;


class Page extends Model
{
    use HasMedia;

	protected $table = 'pages';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function image(){
    	return $this->morphToMany(Media::class, 'mediable')->wherePivot('collection', 'image');
    }

    public function images(){
    	return $this->morphToMany(Media::class, 'mediable')->wherePivot('collection', 'images');
    }
}
