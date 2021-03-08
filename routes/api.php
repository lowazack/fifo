<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('auth/login', '\App\Http\Controllers\API\AuthController@login');

Route::post('auth/forgot', '\App\Http\Controllers\API\AuthController@forgot');

Route::post('auth/reset', '\App\Http\Controllers\API\AuthController@reset');

Route::get('schedule', '\App\Http\Controllers\API\ScheduleController@get');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('me', 'API\MeController@me');

    Route::get('me/tasks', 'API\MeController@tasks');

    Route::apiResource('activities', 'API\ActivityController');

    Route::apiResource('clients', 'API\ClientController');

    Route::apiResource('clients.contacts', 'API\ClientContactController');

    Route::apiResource('events', 'API\EventController');

    Route::apiResource('jobs', 'API\JobController');

    Route::apiResource('jobs.statuses', 'API\JobStatusController');

    Route::apiResource('jobs.tasks', 'API\JobTaskController');

    Route::apiResource('quotes', 'API\QuoteController');

    Route::apiResource('quotes.items', 'API\QuoteItemController');

    Route::apiResource('sales', 'API\SaleController');

    Route::apiResource('skills', 'API\SkillController');

    Route::apiResource('schedules', 'API\ScheduleController');

    Route::apiResource('tasks', 'API\TaskController');

    Route::apiResource('time-entries', 'API\TimeEntryController');

    Route::apiResource('users', 'API\UserController');

});

