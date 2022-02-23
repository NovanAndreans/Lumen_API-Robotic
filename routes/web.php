<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/users', 'UsersControllerAPI@index');

$router->post('/users', 'UsersControllerAPI@store');

$router->get('/users/{id}', 'UsersControllerAPI@show');

$router->post('/users/{id}', 'UsersControllerAPI@update');

$router->delete('/users/{id}', 'UsersControllerAPI@destroy');

$router->post('/login','LoginController@login');
