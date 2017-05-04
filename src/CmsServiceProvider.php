<?php

namespace Helori\LaravelCms;

use Illuminate\Support\ServiceProvider;


class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-cms.php', 'laravel-cms'
        );
    }
    
    public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/views', 'laravel-cms');

		$this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/laravel-cms'),
        ], 'views');

		$this->publishes([
            __DIR__.'/config/laravel-cms.php' => config_path('laravel-cms.php')
        ], 'config');

        $migrations = [];
        $timestamp = date('Y_m_d_His', time());

        if(!class_exists('CreateMediasTable')){
            $migrations[__DIR__.'/migrations/create_medias_table.php'] = database_path('migrations/'.$timestamp.'_create_medias_table.php');
        }
        if(!class_exists('CreateAdminsTable')){
            $migrations[__DIR__.'/migrations/create_admins_table.php'] = database_path('migrations/'.$timestamp.'_create_admins_table.php');
        }
        if(!class_exists('CreateAdminsPasswordResetTable')){
            $migrations[__DIR__.'/migrations/create_admins_password_reset_table.php'] = database_path('migrations/'.$timestamp.'_create_admins_password_reset_table.php');
        }
        if(!class_exists('CreatePagesTable')){
            $migrations[__DIR__.'/migrations/create_pages_table.php'] = database_path('migrations/'.$timestamp.'_create_pages_table.php');
        }
        $this->publishes($migrations, 'migrations');
	}

    public static function routes($routes)
    {
        require(__DIR__.'/routes/'.$routes.'.php');
    }
}
