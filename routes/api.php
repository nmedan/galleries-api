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

Route::resource('galleries', 'GalleriesController');

Route::get('search/{id}', 'GalleriesController@getByAuthor');

Route::get('my-galleries', 'GalleriesController@getByUser');

Route::get('galleries/edit/{id}', 'GalleriesController@edit');

Route::delete('delete-comment/{id}', 'GalleriesController@deleteComment');

Route::post('galleries/{id}/comments', 'GalleriesController@postComment');

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
