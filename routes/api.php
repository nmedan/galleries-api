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

Route::get('authors/{id}', 'GalleriesController@getByAuthorAll');

Route::get('authors/{id}/{currentPage}', 'GalleriesController@getByAuthor');

Route::get('my-galleries', 'GalleriesController@getByUserAll');

Route::get('my-galleries/{currentPage}', 'GalleriesController@getByUser');

Route::get('edit/{id}', 'GalleriesController@edit');

Route::get('filter/{term}/{currentPage}', 'GalleriesController@filter');

Route::get('paginate/{currentPage}', 'GalleriesController@paginate');

Route::post('galleries/{id}/comments', 'GalleriesController@postComment');

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
