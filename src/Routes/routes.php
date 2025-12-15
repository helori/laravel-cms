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

    Route::middleware(['auth:sanctum', Locale::class])
        ->prefix('admin')
        ->group(function ()
    {
        Route::post('/resource/{resource}', [CmsController::class, 'resourceCreate']);
        Route::get('/resource/{resource}', [CmsController::class, 'resourceList']);
        Route::get('/resource/{resource}/{id}', [CmsController::class, 'resourceRead']);
        Route::put('/resource/{resource}/{id}', [CmsController::class, 'resourceUpdate']);
        Route::delete('/resource/{resource}/{id}', [CmsController::class, 'resourceDelete']);
        Route::post('/resource/{resource}/reorder', [CmsController::class, 'resourceReorder']);

        Route::get('/resource/{resource}/{resourceId}/media', [CmsController::class, 'resourceMediaList']);
        Route::get('/resource/{resource}/{resourceId}/media/{mediaId}', [CmsController::class, 'resourceMediaRead']);
        Route::get('/resource/{resource}/{resourceId}/media/{mediaId}/download', [CmsController::class, 'resourceMediaDownload']);
        Route::put('/resource/{resource}/{resourceId}/media/{mediaId}', [CmsController::class, 'resourceMediaUpdate']);
        Route::delete('/resource/{resource}/{resourceId}/media/{mediaId}', [CmsController::class, 'resourceMediaDelete']);
        Route::post('/resource/{resource}/{resourceId}/media/reorder', [CmsController::class, 'resourceMediaReorder']);
    });
});
