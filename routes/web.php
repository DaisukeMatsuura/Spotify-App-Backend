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

$router->group(['prefix' => 'api'], function () use ($router) {
    // jwt 発行のためのルーティング 発行が必要な場合はコメントアウトを外す
    //$router->post('register', 'AuthController@register');

    $router->post('login', 'AuthController@login');
});

// 認証後のAPIルート
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('me', 'AuthController@me');
    $router->get('favorites', 'FavoriteController@index');
    $router->post('favorites', 'FavoriteController@store');
    $router->get('favorites/{favorite_id}', 'FavoriteController@show');
    $router->delete('favorites/{favorite_id}', 'FavoriteController@destroy');
});
