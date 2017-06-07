<?php

function defineCrudRoutes($uri, $controller)
{
    Route::post('/'.$uri, ['uses' => $controller.'@create']);
    Route::get('/'.$uri.'/{id?}', ['uses' => $controller.'@read']);
    Route::put('/'.$uri.'/{id}', ['uses' => $controller.'@update']);
    Route::delete('/'.$uri.'/{id}', ['uses' => $controller.'@delete']);
}


Route::group(['middleware' => ['web']], function ()
{
	Route::group(['prefix' => 'admin'], function ()
	{
		$auth_controllers = '\Helori\LaravelCms\Controllers\Auth\\';

		Route::get('login', ['uses' => $auth_controllers.'LoginController@showLoginForm', 'as' => 'admin-login']);
		Route::post('login', ['uses' => $auth_controllers.'LoginController@login', 'as' => 'admin-post-login']);
		Route::post('logout', ['uses' => $auth_controllers.'LoginController@logout', 'as' => 'admin-logout']);

		Route::get('password-forgot', ['uses' => $auth_controllers.'ForgotPasswordController@showLinkRequestForm', 'as' => 'admin-password-forgot']);
		Route::post('password-email', ['uses' => $auth_controllers.'ForgotPasswordController@sendResetLinkEmail', 'as' => 'admin-password-email']);
		Route::get('password-reset/{token}', ['uses' => $auth_controllers.'ResetPasswordController@showResetForm', 'as' => 'admin-password-reset']);
		Route::post('password-reset', ['uses' => $auth_controllers.'ResetPasswordController@reset', 'as' => 'admin-post-password-reset']);

		Route::group(['middleware' => 'auth:admin'], function ()
		{
			$admin_controllers = '\Helori\LaravelCms\Controllers\Admin\\';

			Route::get('/', ['uses' => $admin_controllers.'FrontController@home', 'as' => 'admin-home']);

			Route::get('/admin', ['uses' => $admin_controllers.'AdminsController@admin', 'as' => 'admin']);
			Route::get('/media', ['uses' => $admin_controllers.'MediasController@media', 'as' => 'media']);
			Route::get('/table', ['uses' => $admin_controllers.'TablesController@table', 'as' => 'table']);
			Route::get('/table/{id}/fields', ['uses' => $admin_controllers.'TablesController@fields', 'as' => 'table-fields']);
			Route::get('/element/{tableId}', ['uses' => $admin_controllers.'ElementsController@element', 'as' => 'element']);
			Route::get('/element/{tableId}/{id}', ['uses' => $admin_controllers.'ElementsController@edit', 'as' => 'element-edit']);

			defineCrudRoutes('/api/admin', $admin_controllers.'AdminsController');
			defineCrudRoutes('/api/media', $admin_controllers.'MediasController');
			defineCrudRoutes('/api/table', $admin_controllers.'TablesController');
			defineCrudRoutes('/api/element/{tableId}', $admin_controllers.'ElementsController');
			
			/*Route::get('/medias', ['uses' => $admin_controllers.'MediasController@read']);
		    Route::post('/medias', ['uses' => $admin_controllers.'MediasController@create']);
		    Route::put('/medias/{id}', ['uses' => $admin_controllers.'MediasController@update']);
		    Route::delete('/medias/{id}', ['uses' => $admin_controllers.'MediasController@delete']);
		    Route::get('/media/{id}/download', ['uses' => $admin_controllers.'MediasController@download']);*/
		});
	});

});
