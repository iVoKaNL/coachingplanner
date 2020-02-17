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

//Route::post('login', 'AuthController@login');
Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@login']);


Route::middleware('auth:api')->group(function () {
    Route::get('students', 'StudentController@index');
    Route::post('student/create', 'StudentController@create');
    Route::get('student/{student}', 'StudentController@show');
    Route::put('student/{student}', 'StudentController@edit');
    Route::delete('student/{student}', 'StudentController@delete');
});