<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
	protected $table = 'medias';
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function deleteFiles()
    {
        if(Storage::exists($this->filepath)){
            Storage::delete($this->filepath);
        }
    }
}
