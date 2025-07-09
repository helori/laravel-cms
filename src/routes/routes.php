<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Helori\LaravelCms\Controllers\CmsController;
use Helori\LaravelCms\Middleware\Locale;
use App\Cms\CmsConfig;

Route::middleware('web')->group(function ()
{
    Route::get('/login', [CmsController::class, 'login'])->name('login');
    Route::post('/login', [CmsController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [CmsController::class, 'logout'])->name('logout');
    
    Route::get('/admin', [CmsController::class, 'admin'])->name('admin')
        ->middleware(['auth:sanctum', Locale::class]);
});

Route::middleware('api')
->prefix('api')
->group(function ()
{
    Route::get('/user', [CmsController::class, 'user']);
    Route::get('/locales', [CmsController::class, 'locales']);
    Route::post('/locale', [CmsController::class, 'locale']);

    Route::middleware(array_merge(['auth:sanctum', Locale::class], CmsConfig::adminMiddlewares()))
        ->prefix('admin')
        ->group(function ()
    {
        Route::post('/resource/{resource}', [CmsController::class, 'resourceCreate']);
        Route::get('/resource/{resource}', [CmsController::class, 'resourceList']);
        Route::get('/resource/{resource}/{id}', [CmsController::class, 'resourceRead']);
        Route::put('/resource/{resource}/{id}', [CmsController::class, 'resourceUpdate']);
        Route::delete('/resource/{resource}/{id}', [CmsController::class, 'resourceDelete']);

        //Route::post('/media', [CmsController::class, 'mediaCreate']);
        //Route::get('/media', [CmsController::class, 'mediaList']);
        Route::get('/media/{id}', [CmsController::class, 'mediaRead']);
        Route::get('/media/{id}/download', [CmsController::class, 'mediaDownload']);
        Route::put('/media/{id}', [CmsController::class, 'mediaUpdate']);
        Route::delete('/media/{id}', [CmsController::class, 'mediaDelete']);
    });
});
