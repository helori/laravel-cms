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
		});
	});

});