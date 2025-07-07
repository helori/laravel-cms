<?php

namespace Helori\LaravelCms;

use Illuminate\Support\ServiceProvider;
use Helori\LaravelCms\Models\Element;
use Illuminate\Support\Facades\Route;


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
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-cms');

        $this->publishes([
            __DIR__.'/config/laravel-cms.php' => config_path('laravel-cms.php'),
        ], 'laravel-cms-config');

        $this->publishesMigrations([
            __DIR__.'/../migrations' => database_path('migrations'),
        ]);
        
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/laravel-cms'),
        ], 'laravel-cms-views');

        $this->publishes([
            __DIR__.'/assets/admin.js' => base_path('resources/js/admin.js'),
            __DIR__.'/assets/admin.scss' => base_path('resources/css/admin.css'),
        ], 'laravel-cms-assets');
	}
}
