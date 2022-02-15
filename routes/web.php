<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/', ['as' => 'index', 'uses' => 'FrontendController@index']);
$router->get('/get-bookings/{order}', ['as' => 'getbookings-order', 'uses' => 'FrontendController@getBookings']);
$router->get('api-doc', ['as' => 'api-doc', 'uses' => 'FrontendController@apiDoc']);

$router->group(['prefix' => 'secret-key-auth'], function() use ($router) {
    $router->get('/', ['as' => 'key-auth.index', 'uses' => 'KeyAuthController@index']);
    $router->post('/', ['as' => 'key-auth.auth', 'uses' => 'KeyAuthController@auth']);
});

$router->group(['prefix' => 'secret-key-management', 'middleware' => 'cookie-auth'], function() use ($router) {
    $router->get('/', ['as' => 'key-management.index', 'uses' => 'KeyManagementController@index']);
    $router->post('/', ['as' => 'key-management.store', 'uses' => 'KeyManagementController@store']);
    $router->get('/{id}', ['as' => 'key-management.edit', 'uses' => 'KeyManagementController@edit']);
    $router->put('/{id}', ['as' => 'key-management.update', 'uses' => 'KeyManagementController@update']);
    $router->delete('{id}', ['as' => 'key-management.destroy', 'uses' => 'KeyManagementController@destroy']);
});

$router->group(['prefix' => 'api'], function() use ($router) {

    $router->group(['middleware' => 'token'], function() use ($router) {
        $router->get('booking', 'BookingApiController@index');
    });

    $router->group(['middleware' => 'token-auth'], function() use ($router) {
        $router->post('booking', 'BookingApiController@store');
        $router->get('booking/{id}', 'BookingApiController@show');
        $router->put('booking/{id}', 'BookingApiController@update');
        $router->delete('booking/{id}', 'BookingApiController@destroy');
    });
});
