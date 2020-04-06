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

Route::get('agenda/moments/{user}', 'AgendaController@getCoachingMoments');
Route::post('agenda/moment/{user}', 'AgendaController@AssignCoachingMoment');

Route::middleware('auth:api')->group(function () {
    Route::get('students', 'StudentController@index');
    Route::post('student', 'StudentController@create');
    Route::get('student/{student}', 'StudentController@show');
    Route::put('student/{student}', 'StudentController@edit');
    Route::delete('student/{student}', 'StudentController@delete');

    Route::post('agenda', 'AgendaController@index');
    Route::post('agenda/update', 'AgendaController@update');
    Route::get('agenda/next', 'AgendaController@getNextCoachingMoment');
    Route::get('agenda/students/notify', 'AgendaController@notifyStudents');
});
