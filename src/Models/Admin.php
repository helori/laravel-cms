<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Helori\LaravelCms\Notifications\AdminResetPassword as ResetPasswordNotification;


class Admin extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'admins';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = ['password', 'remember_token'];
    protected $guarded = [];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
