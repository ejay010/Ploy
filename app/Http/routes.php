<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'guest', 'uses' => 'PagesController@index']);

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('/userlogin', ['middleware' => 'auth', 'uses' => 'UserController@index']);
Route::get('/mapThePosts', 'PostsController@placeOnMap');
Route::post('/saveGeneralPost', 'PostsController@storeGeneralPost');
Route::post('/saveFoodPost', 'PostsController@storeFoodPost');
Route::post('/saveVicePost', 'PostsController@storeVicePost');
Route::post('/saveTransportPost', 'PostsController@storeTransportPost');
Route::post('/userlogin/bidpost', 'BidController@store');
Route::get('/userlogin/bidpost/getbids/{id}', 'BidController@show');
Route::post('/userlogin/mychannel', 'BidController@pusherAuth');
Route::post('/userlogin/dojob/{id}', 'BidController@dojob');
Route::post('/userlogin/accept','BidController@accept');
Route::post('/userlogin/message', 'BidController@relayMessage');

Route::get('/testing', function(){
    dd(\App\job::all()->toArray());
});

