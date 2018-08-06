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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('guest:api')->post('token', 'TokenController@store');

Route::group(['middleware' => 'auth:api'], function($route){
    Route::delete('token', 'TokenController@destroy');
    Route::get('token', 'TokenController@show');

    Route::get('repo', 'OrderController@repo');
    Route::get('repo-search', 'OrderController@repoSearch');
    Route::get('repo-join', 'OrderController@repoJoin');
    Route::get('repo-excel', 'OrderController@repoExcel');
});

