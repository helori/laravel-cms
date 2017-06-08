<?php

namespace Helori\LaravelCms;

use Illuminate\Support\ServiceProvider;
use Helori\LaravelCms\Models\Element;


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
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadTranslationsFrom(__DIR__.'/translations', 'laravel-cms');
        // use echo trans('laravel-cms::file.key');
		$this->loadViewsFrom(__DIR__.'/views', 'laravel-cms');
        // use return view('laravel-cms::folder.view-file');

        $this->publishes([
            __DIR__.'/config/laravel-cms.php' => config_path('laravel-cms.php'),
        ], 'laravel-cms-config');

        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/laravel-cms'),
        ], 'laravel-cms-translations');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/laravel-cms'),
        ], 'laravel-cms-views');

        $this->publishes([
            //__DIR__.'/components' => base_path('resources/assets/js/components/laravel-cms'),
            __DIR__.'/assets/admin.js' => base_path('resources/assets/js/admin.js'),
            __DIR__.'/assets/admin.scss' => base_path('resources/assets/sass/admin.scss'),
            __DIR__.'/assets/tinymce.scss' => base_path('resources/assets/sass/tinymce.scss'),
            __DIR__.'/assets/website.js' => base_path('resources/assets/js/website.js'),
            __DIR__.'/assets/website.scss' => base_path('resources/assets/sass/website.scss'),
        ], 'laravel-cms-assets');
	}
}
