<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Field extends Model
{
    protected $table = 'fields';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [
        'options' => 'array',
        'create' => 'boolean',
        'list' => 'boolean',
    ];
    
    public function fieldset(){
        return $this->belongsTo(Table::class);
    }
}
