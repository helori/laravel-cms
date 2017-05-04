<?php

// Admin connection (using 'admin' guard)
Route::get('admin/login', '\Helori\LaravelCms\Controllers\AdminAuthController@showLoginForm');
Route::post('admin/login', '\Helori\LaravelCms\Controllers\AdminAuthController@login');

// Admin protected routes using 'admin' guard (and 'admin' middleware to protect access)
// Note : we could have used 'auth:admin' middleware instead, but the redirect path could not be customized
Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function ()
{
	Route::get('logout', '\Helori\LaravelCms\Controllers\AdminAuthController@logout');
	Route::get('/', '\Helori\LaravelCms\Controllers\AdminAuthController@welcome');
});