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
$router->get('api-doc', ['as' => 'api-doc', 'uses' => 'FrontendController@apiDoc']);

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
