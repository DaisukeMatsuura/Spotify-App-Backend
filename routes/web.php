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

// jwt 発行およびログインのためのルーティング
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});

// 認証不要のAPIルート
$router->group(['prefix' => 'api/general'], function () use ($router) {
    $router->get('favorites', 'FavoriteController@index');
    $router->post('favorites', 'FavoriteController@store');
    $router->delete('favorites/{favorite_id}', 'FavoriteController@destroy');
});

// 認証後のAPIルート
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('users/{user_id}/favorites', 'FavoriteController@userFavoritesIndex');
    $router->post('users/{user_id}/favorites', 'FavoriteController@userFavoriteCreate');
    $router->delete('users/{user_id}/favorites/{favorite_id}', 'FavoriteController@userFavoriteDestroy');
});



