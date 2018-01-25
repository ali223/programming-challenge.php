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
$router->group(['prefix' => 'api'], function () use ($router) {
	$router->get('tasks', 'TasksController@index');
	$router->post('tasks', 'TasksController@store');
    $router->get('tasks/{id}', 'TasksController@show');
    $router->patch('tasks/{id}', 'TasksController@update');
});
