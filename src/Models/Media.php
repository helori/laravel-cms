<?php

namespace Helori\LaravelCms;

use Illuminate\Database\Eloquent\Model;


class Media extends Model
{
	protected $table = 'medias';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    public function mediable()
    {
        return $this->morphTo();
    }
}
