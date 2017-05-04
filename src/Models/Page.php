<?php

namespace Helori\LaravelCms;

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
        'published' => 'boolean'
    ];

    public function subpages(){
        return $this->hasMany(Page::class, 'parent_id', 'id');
    }

    public function page(){
        return $this->belongsTo(Page::class, 'parent_id', 'id');
    }
}
