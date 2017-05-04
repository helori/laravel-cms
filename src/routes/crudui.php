<?php

$controllers_path = '\Helori\LaravelCms\Controllers';

Route::get('/crud/{model}/items', ['uses' => $controllers_path.'\CrudController@getItems']);
Route::get('/crud/{model}/create-item', ['uses' => $controllers_path.'\CrudController@getCreateItem']);
Route::post('/crud/{model}/store-item', ['uses' => $controllers_path.'\CrudController@postStoreItem']);
Route::get('/crud/{model}/edit-item/{id}', ['uses' => $controllers_path.'\CrudController@getEditItem']);
Route::post('/crud/{model}/update-item/{id}', ['uses' => $controllers_path.'\CrudController@postUpdateItem']);
Route::get('/crud/{model}/delete-item/{id}', ['uses' => $controllers_path.'\CrudController@getDeleteItem']);
Route::post('/crud/{model}/update-position', ['uses' => $controllers_path.'\CrudController@postUpdatePosition']);
Route::post('/crud/{model}/update-field', ['uses' => $controllers_path.'\CrudController@postUpdateField']);

Route::get('/crud/{parent_model}/{parent_id}/{model}/items', ['uses' => $controllers_path.'\CrudController@getItems']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/create-item', ['uses' => $controllers_path.'\CrudController@getCreateItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/store-item', ['uses' => $controllers_path.'\CrudController@postStoreItem']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/edit-item/{id}', ['uses' => $controllers_path.'\CrudController@getEditItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-item/{id}', ['uses' => $controllers_path.'\CrudController@postUpdateItem']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/delete-item/{id}', ['uses' => $controllers_path.'\CrudController@getDeleteItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-position', ['uses' => $controllers_path.'\CrudController@postUpdatePosition']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-field', ['uses' => $controllers_path.'\CrudController@postUpdateField']);

Route::get('/ru/{model}/items', ['uses' => $controllers_path.'\CrudSingleController@getItems']);
Route::post('/ru/{model}/update-item/{id}', ['uses' => $controllers_path.'\CrudSingleController@postUpdateItem']);

Route::post('/medias/{model}/upload-media', ['uses' => $controllers_path.'\MediasController@postUploadMedia']);
Route::post('/medias/{model}/upload-medias', ['uses' => $controllers_path.'\MediasController@postUploadMedias']);
Route::post('/medias/{model}/delete-media', ['uses' => $controllers_path.'\MediasController@postDeleteMedia']);
Route::post('/medias/{model}/rename-media', ['uses' => $controllers_path.'\MediasController@postRenameMedia']);
Route::get('/medias/{model}/media/{id}/{collection}', ['uses' => $controllers_path.'\MediasController@getMedia']);
Route::get('/medias/{model}/medias/{id}/{collection}', ['uses' => $controllers_path.'\MediasController@getMedias']);
Route::post('/medias/{model}/update-medias-position', ['uses' => $controllers_path.'\MediasController@postUpdateMediasPosition']);
Route::post('/medias/{model}/optimize-media', ['uses' => $controllers_path.'\MediasController@postOptimizeMedia']);

Route::get('/global-medias', ['uses' => $controllers_path.'\GlobalMediasController@index']);
Route::get('/global-medias/read-all', ['uses' => $controllers_path.'\GlobalMediasController@readAll']);
Route::post('/global-medias/upload', ['uses' => $controllers_path.'\GlobalMediasController@upload']);
Route::post('/global-medias/delete', ['uses' => $controllers_path.'\GlobalMediasController@delete']);
Route::post('/global-medias/update', ['uses' => $controllers_path.'\GlobalMediasController@update']);
Route::get('/global-medias/download/{id}', ['uses' => $controllers_path.'\GlobalMediasController@download']);
