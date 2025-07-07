<?php

namespace Helori\LaravelCms;

use Illuminate\Support\ServiceProvider;
use Helori\LaravelCms\Models\Element;
use Illuminate\Support\Facades\Route;


class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
	{
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-cms');

        $this->publishes([
            __DIR__.'/Cms' => app_path('Cms'),
        ], 'laravel-cms-config');

        $this->publishesMigrations([
            __DIR__.'/migrations' => database_path('migrations'),
        ], 'laravel-cms-migrations');

        /*$this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/laravel-cms'),
        ], 'laravel-cms-views');*/

        $this->publishes([
            __DIR__.'/assets/admin.js' => base_path('resources/js/admin.js'),
            __DIR__.'/assets/admin-routes.js' => base_path('resources/js/admin-routes.js'),
            __DIR__.'/assets/Admin' => base_path('resources/js/Admin'),
            __DIR__.'/assets/admin.css' => base_path('resources/css/admin.css'),
        ], 'laravel-cms-assets');
	}
}
