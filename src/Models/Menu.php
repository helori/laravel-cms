<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Traits\HasMedia;


class Menu extends Model
{
	use HasMedia;

    protected $table = 'menus';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    public function pages(){
    	return $this->hasMany(Page::class);
    }

    public function image(){
    	return $this->morphToMany(Media::class, 'mediable')->wherePivot('collection', 'image');
    }
}
