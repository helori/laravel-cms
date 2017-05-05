<?php

require_once(__DIR__.'/admin.php');

Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function ()
{
    require_once(__DIR__.'/crudui.php');
});
