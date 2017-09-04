<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Field extends Model
{
	protected $table = 'fields';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    protected $casts = [
    	'use_when_create' => 'boolean',
    	'use_when_update' => 'boolean',
    	'show_in_list' => 'boolean',
    ];

    public function fieldset(){
        return $this->belongsTo(Fieldset::class);
    }
}
