<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Menu extends Model
{
    protected $table = 'menus';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    public function pages(){
    	return $this->hasMany(Page::class);
    }
}
