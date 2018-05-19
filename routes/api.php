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

// For debugging / performance monitoring SQL
// \DB::listen(function($query) {    \Log::info($query->sql);    \Log::info($query->bindings);    \Log::info($query->time); });

/**
 * Welcome route - link to any public API documentation here
 */
Route::get('/', function () {
    echo 'Welcome to our API';
});


/**
 * @var $api \Dingo\Api\Routing\Router
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => ['api']], function ($api) {
    /**
     * Authentication
     */
    $api->group(['prefix' => 'auth'], function($api) {
        $api->group(['prefix' => 'jwt'], function($api) {
            $api->get('/login', 'App\Http\Controllers\Auth\AuthController@login');
            $api->get('/token', 'App\Http\Controllers\Auth\AuthController@token');
            $api->get('/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->delete('/token', 'App\Http\Controllers\Auth\AuthController@logout');
        });

        $api->get('/me', 'App\Http\Controllers\Auth\AuthController@getUser');
    });

    /**
     * Users
     */
    $api->group(['prefix' => 'users'], function($api) {
        $api->get('/', 'App\Http\Controllers\UserController@getAll');
        $api->get('/{uuid}', 'App\Http\Controllers\UserController@get');
        $api->post('/', 'App\Http\Controllers\UserController@post');
        $api->put('/{uuid}', 'App\Http\Controllers\UserController@put');
        $api->patch('/{uuid}', 'App\Http\Controllers\UserController@patch');
        $api->delete('/{uuid}', 'App\Http\Controllers\UserController@delete');

        /**
         * Users' Campaigns
         */
        $api->group(['prefix' => '/{uuid}/campaigns'], function($api) {
            $api->post('/', 'App\Http\Controllers\UserCampaignController@post');
            $api->get('/', 'App\Http\Controllers\UserCampaignController@getAll');
            $api->get('/{campaignUuid}', 'App\Http\Controllers\UserCampaignController@get');
        });
    });

    /**
     * Campaigns
     */
    $api->group(['prefix' => 'campaigns'], function($api) {
        $api->get('/', 'App\Http\Controllers\CampaignController@getAll');
    });

    /**
     * Roles
     */
    $api->group(['prefix' => 'roles'], function($api) {
        $api->get('/', 'App\Http\Controllers\RoleController@getAll');
    });

    /**
     * Campaign tags
     */
    $api->group(['prefix' => '/campaigns'], function($api) {
        $api->post('/{campaignUuid}/tags', 'App\Http\Controllers\CampaignTagController@post');
    });
});
