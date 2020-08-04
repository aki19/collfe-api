<?php

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/items', ['uses' => 'ItemsController@index']);
    $router->post('/items', 'ItemsController@create');
    $router->get('/items/{id}', 'ItemsController@show');
    $router->put('/items/{id}', 'ItemsController@update');
    $router->delete('/items/{id}', 'ItemsController@destroy');

    $router->get('/categorys', ['uses' => 'CategoriesController@index']);
    $router->get('/categorys/{id}', 'CategoriesController@show');
});
