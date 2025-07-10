<?php

namespace Helori\LaravelCms;

use Illuminate\Support\ServiceProvider;
use Helori\LaravelCms\Models\Element;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\Json\JsonResource;


class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
	{
        JsonResource::withoutWrapping();

        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'laravel-cms');

        $this->publishes([
            __DIR__.'/Cms' => app_path('Cms'),
        ], 'laravel-cms-config');

        $this->publishesMigrations([
            __DIR__.'/Migrations' => database_path('migrations'),
        ], 'laravel-cms-migrations');

        $this->publishes([
            __DIR__.'/Views' => base_path('resources/views/vendor/laravel-cms'),
        ], 'laravel-cms-views');

        $this->publishes([
            __DIR__.'/Publish/setup/package.json' => base_path('package.json'),
            __DIR__.'/Publish/setup/postcss.config.mjs' => base_path('postcss.config.mjs'),
            __DIR__.'/Publish/setup/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/Publish/setup/vite.config.js' => base_path('vite.config.js'),
        ], 'laravel-cms-assets-setup');

        $this->publishes([
            __DIR__.'/Publish/admin/admin.js' => base_path('resources/js/admin.js'),
            __DIR__.'/Publish/admin/admin-routes.js' => base_path('resources/js/admin-routes.js'),
            __DIR__.'/Publish/admin/Admin' => base_path('resources/js/Admin'),
            __DIR__.'/Publish/admin/admin.css' => base_path('resources/css/admin.css'),
            __DIR__.'/Publish/admin/lang' => base_path('lang'),
            __DIR__.'/Publish/admin/api.php' => base_path('routes/api.php'),
            __DIR__.'/Publish/admin/Resources/User.php' => app_path('Http/Resources/User.php'),
        ], 'laravel-cms-assets-admin');

        $this->publishes([
            __DIR__.'/Publish/website/WebsiteController.php' => app_path('Http/Controllers/WebsiteController.php'),
            __DIR__.'/Publish/website/website.js' => base_path('resources/js/website.js'),
            __DIR__.'/Publish/website/website.css' => base_path('resources/css/website.css'),
            __DIR__.'/Publish/website/layout.blade.php' => base_path('resources/views/layout.blade.php'),
            __DIR__.'/Publish/website/home.blade.php' => base_path('resources/views/home.blade.php'),
            __DIR__.'/Publish/website/web.php' => base_path('routes/web.php'),
        ], 'laravel-cms-assets-website');
	}
}
