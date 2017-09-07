<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Fieldset extends Model
{
    protected $table = 'fieldsets';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    protected $casts = [
    	'single' => 'boolean',
    ];

    public function fields(){
        return $this->hasMany(Field::class);
    }
}
