<?php

Route::middleware('web')->group(function () {

    Route::group(['prefix' => 'admin'], function ($router) {
                    
        $controllers = '\Helori\LaravelCms\Controllers\\';

        Route::get('login', [
            'uses' => $controllers.'Auth\LoginController@showLoginForm', 
            'as' => 'admin-login'
        ]);

        Route::post('login', [
            'uses' => $controllers.'Auth\LoginController@login', 
            'as' => 'admin-post-login'
        ]);

        Route::post('logout', [
            'uses' => $controllers.'Auth\LoginController@logout', 
            'as' => 'admin-logout'
        ]);

        Route::get('password-forgot', [
            'uses' => $controllers.'Auth\ForgotPasswordController@showLinkRequestForm', 
            'as' => 'admin-password-forgot'
        ]);

        Route::post('password-email', [
            'uses' => $controllers.'Auth\ForgotPasswordController@sendResetLinkEmail', 
            'as' => 'admin-password-email'
        ]);

        Route::get('password-reset/{token}', [
            'uses' => $controllers.'Auth\ResetPasswordController@showResetForm', 
            'as' => 'admin-password-reset'
        ]);

        Route::post('password-reset', [
            'uses' => $controllers.'Auth\ResetPasswordController@reset', 
            'as' => 'admin-post-password-reset'
        ]);

        Route::group(['middleware' => 'auth:admin'], function () use($controllers)
        {
            Route::get('/', [
                'uses' => $controllers.'FrontController@home', 
                'as' => 'admin-home'
            ]);

            Route::get('/medias', [
                'uses' => $controllers.'FrontController@medias', 
                'as' => 'admin-medias'
            ]);

            Route::get('/blog', [
                'uses' => $controllers.'FrontController@blog', 
                'as' => 'admin-blog'
            ]);

            Route::get('/pages', [
                'uses' => $controllers.'FrontController@pages', 
                'as' => 'admin-pages'
            ]);

            Route::get('/fieldsets', [
                'uses' => $controllers.'FrontController@fieldsets', 
                'as' => 'admin-fieldsets'
            ]);

            Route::get('/elements/{slug}', [
                'uses' => $controllers.'FrontController@elements', 
                'as' => 'admin-elements'
            ]);

            Route::post('/api/media', ['uses' => $controllers.'MediasController@create']);
            Route::get('/api/media/{id?}', ['uses' => $controllers.'MediasController@read']);
            Route::put('/api/media/{id}', ['uses' => $controllers.'MediasController@update']);
            Route::delete('/api/media/{id}', ['uses' => $controllers.'MediasController@delete']);
            Route::get('/api/media/{id}/download', ['uses' => $controllers.'MediasController@download']);

            Route::post('/api/menu', ['uses' => $controllers.'MenusController@create']);
            Route::get('/api/menu/{id?}', ['uses' => $controllers.'MenusController@read']);
            Route::put('/api/menu/{id}', ['uses' => $controllers.'MenusController@update']);
            Route::delete('/api/menu/{id}', ['uses' => $controllers.'MenusController@delete']);

            Route::post('/api/page', ['uses' => $controllers.'PagesController@create']);
            Route::get('/api/page/{id?}', ['uses' => $controllers.'PagesController@read']);
            Route::put('/api/page/{id}', ['uses' => $controllers.'PagesController@update']);
            Route::delete('/api/page/{id}', ['uses' => $controllers.'PagesController@delete']);

            Route::post('/api/blog-article', ['uses' => $controllers.'BlogArticlesController@create']);
            Route::get('/api/blog-article/{id?}', ['uses' => $controllers.'BlogArticlesController@read']);
            Route::put('/api/blog-article/{id}', ['uses' => $controllers.'BlogArticlesController@update']);
            Route::delete('/api/blog-article/{id}', ['uses' => $controllers.'BlogArticlesController@delete']);

            Route::post('/api/blog-category', ['uses' => $controllers.'BlogCategoriesController@create']);
            Route::get('/api/blog-category/{id?}', ['uses' => $controllers.'BlogCategoriesController@read']);
            Route::put('/api/blog-category/{id}', ['uses' => $controllers.'BlogCategoriesController@update']);
            Route::delete('/api/blog-category/{id}', ['uses' => $controllers.'BlogCategoriesController@delete']);

            Route::post('/api/fieldset', ['uses' => $controllers.'FieldsetsController@create']);
            Route::get('/api/fieldset/{id?}', ['uses' => $controllers.'FieldsetsController@read']);
            Route::put('/api/fieldset/{id}', ['uses' => $controllers.'FieldsetsController@update']);
            Route::delete('/api/fieldset/{id}', ['uses' => $controllers.'FieldsetsController@delete']);

            Route::post('/api/fieldset/{fieldsetId}/field', ['uses' => $controllers.'FieldsController@create']);
            Route::get('/api/fieldset/{fieldsetId}/field/{id?}', ['uses' => $controllers.'FieldsController@read']);
            Route::put('/api/fieldset/{fieldsetId}/field/{id}', ['uses' => $controllers.'FieldsController@update']);
            Route::delete('/api/fieldset/{fieldsetId}/field/{id}', ['uses' => $controllers.'FieldsController@delete']);

            Route::post('/api/fieldset/{fieldsetId}/element', ['uses' => $controllers.'ElementsController@create']);
            Route::get('/api/fieldset/{fieldsetId}/element/{id?}', ['uses' => $controllers.'ElementsController@read']);
            Route::put('/api/fieldset/{fieldsetId}/element/{id}', ['uses' => $controllers.'ElementsController@update']);
            Route::delete('/api/fieldset/{fieldsetId}/element/{id}', ['uses' => $controllers.'ElementsController@delete']);
        });
    });

});