<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('galleries/index/{currentPage}', 'GalleriesController@index');

Route::get('galleries/index/{currentPage}/{term}', 'GalleriesController@filterAllGalleries');

Route::get('galleries/details/{id}', 'GalleriesController@show');

Route::get('authors/{id}/{currentPage}', 'GalleriesController@getByAuthor');

Route::get('authors/{id}/{currentPage}/{term}', 'GalleriesController@filterAuthorsGalleries');

Route::get('my-galleries/{currentPage}', 'GalleriesController@getByUser');

Route::get('my-galleries/{currentPage}/{term}', 'GalleriesController@filterUsersGalleries');

Route::get('edit/{id}', 'GalleriesController@edit');

Route::post('galleries', 'GalleriesController@store');

Route::post('galleries/{id}/comments', 'GalleriesController@postComment');

Route::put('galleries/{id}', 'GalleriesController@update');

Route::delete('galleries/{id}/comments', 'GalleriesController@deleteComment');

Route::group([   
    'middleware' => 'api',
    'prefix' => 'auth'    
], function ($router) {   
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');    
});
