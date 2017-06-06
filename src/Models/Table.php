<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;


class Table extends Model
{
    protected $table = 'tables';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $casts = [
        'in_admin' => 'boolean',
        'multiple' => 'boolean',
    ];

    public function fields(){
        return $this->hasMany(Field::class);
    }
}
