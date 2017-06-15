<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Fieldset extends Model
{
    protected $table = 'fieldsets';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [
        
    ];

    public function fields(){
        return $this->hasMany(Field::class);
    }
}
