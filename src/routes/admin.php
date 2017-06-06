<?php

// Admin connection (using 'admin' guard)
/*Route::get('admin/login', '\Helori\LaravelCms\Controllers\Admin\AuthController@showLoginForm');
Route::post('admin/login', '\Helori\LaravelCms\Controllers\AdminAuthController@login');

// Admin protected routes using 'admin' guard (and 'admin' middleware to protect access)
// Note : we could have used 'auth:admin' middleware instead, but the redirect path could not be customized
Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function ()
{
	Route::get('logout', '\Helori\LaravelCms\Controllers\AdminAuthController@logout');
	Route::get('/', '\Helori\LaravelCms\Controllers\AdminAuthController@welcome');
});*/



Route::group(['middleware' => ['web']], function ()
{

	Route::group(['prefix' => 'admin'], function ()
	{
		$admin_controllers = '\Helori\LaravelCms\Controllers\Admin\\';

		Route::get('login', ['uses' => $admin_controllers.'LoginController@showLoginForm', 'as' => 'admin-login']);
		Route::post('login', ['uses' => $admin_controllers.'LoginController@login', 'as' => 'admin-post-login']);
		Route::post('logout', ['uses' => $admin_controllers.'LoginController@logout', 'as' => 'admin-logout']);

		Route::get('password-forgot', ['uses' => $admin_controllers.'ForgotPasswordController@showLinkRequestForm', 'as' => 'admin-password-forgot']);
		Route::post('password-email', ['uses' => $admin_controllers.'ForgotPasswordController@sendResetLinkEmail', 'as' => 'admin-password-email']);
		Route::get('password-reset/{token}', ['uses' => $admin_controllers.'ResetPasswordController@showResetForm', 'as' => 'admin-password-reset']);
		Route::post('password-reset', ['uses' => $admin_controllers.'ResetPasswordController@reset', 'as' => 'admin-post-password-reset']);

		Route::group(['middleware' => 'auth:admin'], function () use($admin_controllers)
		{
			Route::get('/', ['uses' => $admin_controllers.'FrontController@home', 'as' => 'admin-home']);

			Route::get('/medias-manager', ['uses' => $admin_controllers.'MediasController@medias', 'as' => 'medias-manager']);
			
			Route::get('/medias', ['uses' => $admin_controllers.'MediasController@read']);
		    Route::post('/medias', ['uses' => $admin_controllers.'MediasController@create']);
		    Route::put('/medias/{id}', ['uses' => $admin_controllers.'MediasController@update']);
		    Route::delete('/medias/{id}', ['uses' => $admin_controllers.'MediasController@delete']);
		    Route::get('/media/{id}/download', ['uses' => $admin_controllers.'MediasController@download']);

		    Route::get('/tables-manager', ['uses' => $admin_controllers.'TablesController@tables', 'as' => 'tables-manager']);

		    Route::get('/tables/{id?}', ['uses' => $admin_controllers.'TablesController@read']);
		    Route::post('/tables', ['uses' => $admin_controllers.'TablesController@create']);
		    Route::put('/tables/{id}', ['uses' => $admin_controllers.'TablesController@update']);
		    Route::delete('/tables/{id}', ['uses' => $admin_controllers.'TablesController@delete']);

		    Route::get('/table-manager/{table_id}', ['uses' => $admin_controllers.'TableController@table', 'as' => 'table-manager']);

		    Route::get('/table/{table_id}/{id?}', ['uses' => $admin_controllers.'TableController@read']);
		    Route::post('/table/{table_id}', ['uses' => $admin_controllers.'TableController@create']);
		    Route::put('/table/{table_id}/{id}', ['uses' => $admin_controllers.'TableController@update']);
		    Route::delete('/table/{table_id}/{id}', ['uses' => $admin_controllers.'TableController@delete']);
		});
	});

});