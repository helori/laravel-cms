<?php

namespace Helori\LaravelCms;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'admins';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = ['password', 'remember_token'];
    protected $guarded = [];
}
