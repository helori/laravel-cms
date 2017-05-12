<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Field extends Model
{
    protected $table = 'fields';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    public function table(){
        return $this->belongsTo(Table::class);
    }
}
