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

$router->group(['prefix' => 'eproc'], function () use ($router) {
    
    $router->group(['prefix' => 'v1'], function () use ($router) {
        //Comments
        $router->group(['prefix'=>'comments'],function() use ($router){
            $router->get('/','CommentsController@index');
            $router->get('/{module}','CommentsController@comments');
            $router->post('/store','CommentsController@store');
            $router->post('/update/{id}','CommentsController@update');
            $router->delete('/delete','CommentsController@delete');
        });

        //Unit
        $router->group(['prefix'=>'unit'],function() use ($router){
            $router->get('/','UnitController@index');
            $router->get('/{id}','UnitController@detail');
            $router->post('/store','UnitController@store');
            $router->post('/update/{id}','UnitController@update');
            $router->delete('/delete','UnitController@delete');
        });

        //Likes
        $router->group(['prefix'=>'likes'], function () use ($router){
            $router->get('/{module}','LikesController@getLikes');
            $router->post('/like','LikesController@like');
            $router->delete('/unlike','LikesController@unlike');
        });
    });
});
