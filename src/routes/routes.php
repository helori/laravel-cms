<?php

function defineCrudRoutes($uri, $controller)
{
    Route::post('/'.$uri, ['uses' => $controller.'@create']);
    Route::get('/'.$uri.'/{id?}', ['uses' => $controller.'@read']);
    Route::put('/'.$uri.'/{id}', ['uses' => $controller.'@update']);
    Route::delete('/'.$uri.'/{id}', ['uses' => $controller.'@delete']);

    Route::put('/'.$uri.'-position', ['uses' => $controller.'@position']);
    Route::post('/'.$uri.'-searchable', ['uses' => $controller.'@searchable']);
}


Route::group(['middleware' => ['web']], function ()
{
	// Admin routes

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

			Route::get('/admin', ['uses' => $admin_controllers.'AdminsController@admin', 'as' => 'admin-admin']);
			Route::get('/media', ['uses' => $admin_controllers.'MediasController@media', 'as' => 'admin-media']);
			Route::get('/fieldset', ['uses' => $admin_controllers.'FieldsetsController@fieldset', 'as' => 'admin-fieldset']);
			Route::get('/fieldset/{fieldsetId}/field', ['uses' => $admin_controllers.'FieldsController@field', 'as' => 'admin-field']);
			Route::get('/page', ['uses' => $admin_controllers.'PagesController@page', 'as' => 'admin-page']);
			Route::get('/page/{id}', ['uses' => $admin_controllers.'PagesController@edit', 'as' => 'page-edit']);
			Route::get('/collection', ['uses' => $admin_controllers.'CollectionsController@collection', 'as' => 'admin-collection']);
			Route::get('/collection/{collectionId}/post', ['uses' => $admin_controllers.'PostsController@post', 'as' => 'admin-post']);
			Route::get('/collection/{collectionId}/post/{id}', ['uses' => $admin_controllers.'PostsController@edit', 'as' => 'admin-post-edit']);

			Route::get('/table', ['uses' => $admin_controllers.'TablesController@table', 'as' => 'admin-table']);
			Route::get('/table/{id}/fields', ['uses' => $admin_controllers.'TablesController@fields', 'as' => 'admin-table-fields']);
			Route::get('/element/{tableId}', ['uses' => $admin_controllers.'ElementsController@element', 'as' => 'admin-element']);
			Route::get('/element/{tableId}/{id}', ['uses' => $admin_controllers.'ElementsController@edit', 'as' => 'admin-element-edit']);

			defineCrudRoutes('/api/admin', $admin_controllers.'AdminsController');
			defineCrudRoutes('/api/media', $admin_controllers.'MediasController');
			defineCrudRoutes('/api/page', $admin_controllers.'PagesController');
			defineCrudRoutes('/api/fieldset', $admin_controllers.'FieldsetsController');
			defineCrudRoutes('/api/fieldset/{fieldsetId}/field', $admin_controllers.'FieldsController');
			defineCrudRoutes('/api/collection', $admin_controllers.'CollectionsController');
			defineCrudRoutes('/api/collection/{collectionId}/post', $admin_controllers.'PostsController');

			defineCrudRoutes('/api/table', $admin_controllers.'TablesController');
			defineCrudRoutes('/api/element/{tableId}', $admin_controllers.'ElementsController');
			
			/*Route::get('/medias', ['uses' => $admin_controllers.'MediasController@read']);
		    Route::post('/medias', ['uses' => $admin_controllers.'MediasController@create']);
		    Route::put('/medias/{id}', ['uses' => $admin_controllers.'MediasController@update']);
		    Route::delete('/medias/{id}', ['uses' => $admin_controllers.'MediasController@delete']);
		    Route::get('/media/{id}/download', ['uses' => $admin_controllers.'MediasController@download']);*/
		});
	});

	// Public routes

	//$public_controllers = '\Helori\LaravelCms\Controllers\Front\\';
	$public_controllers = '\App\Http\Controllers\\';

	Route::get('/', ['uses' => $public_controllers.'FrontController@home', 'as' => 'home']);
	Route::get('/{slug}', ['uses' => $public_controllers.'FrontController@page', 'as' => 'page']);
	Route::get('/{pageSlug}/{slug}', ['uses' => $public_controllers.'FrontController@post', 'as' => 'post']);

});
